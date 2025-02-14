<?php

namespace Alpayklncrsln\RuleSchema\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RuleSchemaCommand extends Command
{
    protected $signature = 'make:rule-schema {name} {--m}';

    protected $description = 'RuleSchema request creation';

    public function handle(): int
    {
        $name = $this->argument('name');
        $stubPath = __DIR__.'/../stubs/rule-schema.stub';
        $namespace = 'App\\Http\\Requests';
        $targetPath = base_path("app/Http/Requests/{$name}Request.php");

        if (! File::exists($stubPath)) {
            $this->error("Stub not found: {$stubPath}");

            return self::FAILURE;
        }

        $rules = '';

        if ($this->option('m')) {
            $modelClass = "App\\Models\\{$name}";

            if (! class_exists($modelClass)) {
                $this->error("Model not found: {$modelClass}");

                return self::FAILURE;
            }

            /** @var \Illuminate\Database\Eloquent\Model $model */
            $model = new $modelClass;
            $table = $model->getTable();
            $fillableColumns = $model->getFillable();

            if (! DB::getSchemaBuilder()->hasTable($table)) {
                $this->error("Table not found: {$table}");

                return self::FAILURE;
            }

            // Tablodaki tüm sütunları al
            $columns = DB::select("PRAGMA table_info($table)");

            if (empty($fillableColumns)) {
                $filteredColumns = $columns;
            } else {
                $filteredColumns = array_filter($columns, fn ($col) => in_array($col->name, $fillableColumns));
            }

            foreach ($filteredColumns as $column) {
                $columnName = $column->name;

                if (in_array($columnName, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    continue;
                }

                $nullable = $column->notnull == 0;
                $dataType = strtolower($column->type);

                $rule = "Rule::make('{$columnName}')";

                if ($nullable) {
                    $rule .= '->nullable()';
                } else {
                    $rule .= '->required()';
                }

                if (str_contains($dataType, 'int')) {
                    $rule .= '->integer()';
                } elseif (str_contains($dataType, 'varchar') || str_contains($dataType, 'text')) {
                    $rule .= '->string()';

                    if (preg_match('/\((\d+)\)/', $column->type, $matches)) {
                        $rule .= "->max({$matches[1]})";
                    }
                } elseif (str_contains($dataType, 'decimal') || str_contains($dataType, 'double') || str_contains($dataType, 'float')) {
                    $rule .= '->numeric()';
                } elseif (str_contains($dataType, 'date') || str_contains($dataType, 'time') || str_contains($dataType, 'timestamp')) {
                    $rule .= '->date()';
                } elseif (str_contains($dataType, 'boolean') || str_contains($dataType, 'tinyint(1)')) {
                    $rule .= '->boolean()';
                }

                if ($columnName === 'email') {
                    $rule .= '->email()';
                } elseif ($columnName === 'url') {
                    $rule .= '->url()';
                }

                if (! $nullable && str_contains($column->type, 'unique')) {
                    $rule .= "->unique('{$table}', '{$columnName}')";
                }

                if (! is_null($column->dflt_value)) {
                    $rule .= '->sometimes()';
                }
                $rules .= "            {$rule},\n";
            }
        }

        $stubContent = File::get($stubPath);
        $stubContent = str_replace(
            ['{{namespace}}', '{{class}}', '{{content}}'],
            [$namespace, "{$name}Request", rtrim($rules, ",\n")],
            $stubContent
        );

        File::ensureDirectoryExists(dirname($targetPath));
        File::put($targetPath, $stubContent);

        $this->info("Request class created: {$targetPath}");

        return self::SUCCESS;
    }
}
