<?php

namespace Alpayklncrsln\RuleSchema\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RuleSchemaCommand extends Command
{
    protected $signature = 'make:rule-schema {name} {--m}';

    protected $description = 'RuleSchema request creation';

    public function handle(): int
    {
        $stubPath = __DIR__.'/../stubs/rule-schema.stub';
        $name = str_replace('/', '\\', $this->argument('name'));
        $namespace = 'App\\Http\\Requests'.(Str::beforeLast($name, '\\') != $name ? '\\'.trim(Str::after($name, '\\'), '\\') : '');
        $targetPath = base_path('app/Http/Requests/'.str_replace(['\\', '/'], '/', $name).(! Str::contains($name, 'Request') ? 'Request' : '').'.php');

        if (File::exists($targetPath)) {
            $this->error("Request already exists: {$targetPath}");

            return self::FAILURE;
        }
        $name = Str::afterLast($name, '\\');

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

            $model = new $modelClass;
            $table = $model->getTable();
            $fillableColumns = $model->getFillable();

            if (! DB::getSchemaBuilder()->hasTable($table)) {
                $this->error("Table not found: {$table}");

                return self::FAILURE;
            }

            $columns = $this->getTableColumns($model, $table);

            if (empty($fillableColumns)) {
                $filteredColumns = array_map(fn ($col) => (object) ['name' => $this->getColumnName($col)], $columns);
            } else {
                $filteredColumns = array_filter($columns, fn ($col) => in_array($this->getColumnName($col), $fillableColumns));
            }

            foreach ($filteredColumns as $column) {
                $columnName = $this->getColumnName($column);
                if (in_array($columnName, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                    continue;
                }

                $nullable = $this->isNullable($column);
                $dataType = $this->getColumnType($column);

                $rule = "Rule::make('{$columnName}')";
                $rule .= $nullable ? '->nullable()' : '->required()';

                if (str_contains($dataType, 'int')) {
                    $rule .= '->integer()';
                } elseif (str_contains($dataType, 'varchar') || str_contains($dataType, 'text')) {
                    $rule .= '->string()';
                    if (preg_match('/\((\d+)\)/', $dataType, $matches)) {
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

                $rules .= "            {$rule},\n";
            }
        }

        $stubContent = File::get($stubPath);
        $stubContent = str_replace(
            ['{{namespace}}', '{{class}}', '{{content}}'],
            [$namespace, $name.($this->option('m') || ! Str::contains($name, 'Request') ? 'Request' : ''), rtrim($rules, ",\n")],
            $stubContent
        );

        File::ensureDirectoryExists(dirname($targetPath));
        File::put($targetPath, $stubContent);
        $this->info("Request class created: {$targetPath}");

        return self::SUCCESS;
    }

    private function getTableColumns($model, $table)
    {
        switch ($model->getConnection()->getDriverName()) {
            case 'sqlite':
                return DB::select("PRAGMA table_info(`$table`)");
            case 'mysql':
                return DB::select("SHOW COLUMNS FROM `$table`");
            case 'pgsql':
            case 'mssql':
                return DB::select('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE LOWER(table_name) = LOWER(?)', [$table]);
            case 'oracle':
                return DB::select('SELECT * FROM ALL_TAB_COLUMNS WHERE UPPER(TABLE_NAME) = UPPER(?)', [$table]);
            default:
                throw new \Exception("Unsupported database driver: {$model->getConnection()->getDriverName()}");
        }
    }

    private function getColumnName($column)
    {
        return $column->name ?? $column->Field ?? $column->column_name ?? null;
    }

    private function getColumnType($column)
    {
        return strtolower($column->type ?? $column->Type ?? $column->data_type ?? '');
    }

    private function isNullable($column)
    {
        return ! ($column->notnull ?? $column->Null === 'NO' ?? false);
    }
}
