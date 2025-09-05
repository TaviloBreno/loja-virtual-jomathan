<?php

use App\Infrastructure\Database\Migration;
use App\Infrastructure\Database\Schema;

/**
 * Migração de exemplo - Tabela de usuários
 * Demonstra o uso do sistema de migrações
 */
class CreateUsersTable extends Migration
{
    /**
     * Executa a migração
     */
    public function up(): void
    {
        Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->json('preferences')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['email']);
            $table->index(['status']);
            $table->index(['created_at']);
        });
    }

    /**
     * Desfaz a migração
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}