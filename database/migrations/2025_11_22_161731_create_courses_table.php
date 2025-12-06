<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('imagen');
            $table->text('descripcion')->nullable();
            $table->string('nivel');
            $table->string('categoria');
            $table->string('url_video_intro')->nullable();
            $table->integer('orden');
            $table->string('duracion')->default('1 hora');
            $table->integer('vistas')->default(0);
            $table->decimal('precio_actual', 10, 2);
            $table->decimal('precio_anterior', 10, 2)->nullable();
            $table->json('contenido')->nullable();
            $table->string('autor')->default('Chris Gámez');
            $table->timestamps();
        });

        //crear 6 curso demo
        DB::table('courses')->insert([
            [
                'orden' => 1,
                'titulo' => 'Fundamentos de VUE.js',
                'imagen' => 'https://vueschool.io/_ipx/f_webp&q_100&s_2400x1350/https://vueschool.io/storage/media/a57314fe25051ebeddf614d6cd1df47d/Nuxt_Auth-02.jpeg',
                'descripcion' => 'Aprende los conceptos fundamentales y ponte manos a la obra con Vue.js 3 con la API de Composition. ¡Ideal si no has trabajado con Vue o si apenas estás empezando!',
                'nivel' => 'Principiante',
                'categoria' => 'Desarrollo Web',
                'vistas' => 0,
                'precio_actual' => 10.99,
                'precio_anterior' => 30.99,
                'url_video_intro' => 'https://www.dailymotion.com/embed/video/k4WljjJFB66A4eDKHaK?autoplay=1&ui-logo=0&sharing-enable=0&queue-enable=0',
                'duracion' => '1 Hora',
                'contenido' => json_encode([
                    [
                        "order" => 1,
                        "title" => "Módulo 1: Fundamentos de JS y Entorno",
                        "isExpanded" => true,
                        "lessons" => [
                            [
                                "id" => 1,
                                "title" => "1.1 Introducción y Configuración",
                                "duration" => "5m",
                                "type" => "video",
                                "order" => 1
                                ,"isFree"=> true
                            ],
                            [
                                "id" => 2,
                                "title" => "1.2 Tipos de Datos Primitivos",
                                "duration" => "10m",
                                "type" => "video",
                                "order" => 2
                                ,"isFree"=> true
                            ],
                            [
                                "id" => 3,
                                "title" => "1.3 Variables (let vs const)",
                                "duration" => "8m",
                                "type" => "markdown",
                                "order" => 3
                                ,"isFree"=> true
                            ],
                            [
                                "id" => 4,
                                "title" => "1.4 Operadores y Lógica Booleana",
                                "duration" => "12m",
                                "type" => "video",
                                "order" => 4
                                ,"isFree"=> false
                            ],
                        ],
                    ],

                    [
                        "order" => 2,
                        "title" => "Módulo 2: Control de Flujo y Funciones",
                        "isExpanded" => false,
                        "lessons" => [
                            [
                                "id" => 5,
                                "title" => "2.1 Condicionales (if/else/switch)",
                                "duration" => "15m",
                                "type" => "video",
                                "order" => 1
                                ,"isFree"=> false
                            ],
                            [
                                "id" => 6,
                                "title" => "2.2 Bucles (for/while/do-while)",
                                "duration" => "18m",
                                "type" => "video",
                                "order" => 2
                                ,"isFree"=> false
                            ],
                            [
                                "id" => 7,
                                "title" => "2.3 Funciones clásicas y Arrow Functions",
                                "duration" => "25m",
                                "type" => "markdown",
                                "order" => 3,
                                "isFree"=> false
                            ],
                        ],
                    ],

                    [
                        "order" => 3,
                        "title" => "Módulo 3: Asincronía Avanzada",
                        "isExpanded" => false,
                        "lessons" => [
                            [
                                "id" => 8,
                                "title" => "3.1 Callbacks y Callback Hell",
                                "duration" => "15m",
                                "type" => "video",
                                "order" => 1,
                                "isFree"=> false
                            ],
                            [
                                "id" => 9,
                                "title" => "3.2 Introducción a las Promesas",
                                "duration" => "25m",
                                "type" => "video",
                                "order" => 2,
                                "isFree"=> false
                            ],
                            [
                                "id" => 10,
                                "title" => "3.3 Async/Await: Limpieza de Código Asíncrono",
                                "duration" => "35m",
                                "type" => "video",
                                "order" => 3,
                                "isFree"=> false
                            ],
                        ],
                    ],


                    // Agrega más lecciones según sea necesario
                ]),
                'autor' => 'Chris Gámez'
            ],
            /* ============================
       CURSO 2
    ============================= */
            [
                'orden' => 2,
                'titulo' => 'Laravel desde Cero a Senior',
                'imagen' => 'https://laravel.com/img/logomark.min.svg',
                'descripcion' => 'Aprende Laravel paso a paso desde los fundamentos hasta construir APIs avanzadas, autenticación, colas, caché y servicios.',
                'nivel' => 'Intermedio',
                'categoria' => 'Backend',
                'vistas' => 0,
                'precio_actual' => 14.99,
                'precio_anterior' => 59.99,
                'url_video_intro' => 'https://www.dailymotion.com/embed/video/k4WljjJFB66A4eDKHaK?autoplay=1&ui-logo=0&sharing-enable=0&queue-enable=0',
                'duracion' => '3 Horas',
                'contenido' => json_encode([
                    [
                        "order" => 1,
                        "title" => "Fundamentos de Laravel",
                        "isExpanded" => true,
                        "lessons" => [
                            ["id" => 1, "title" => "1.1 Instalación y Configuración", "duration" => "8m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 2, "title" => "1.2 Rutas y Controladores", "duration" => "12m", "type" => "video", "order" => 2,"isFree"=> false],
                            ["id" => 3, "title" => "1.3 Blade Templates", "duration" => "15m", "type" => "video", "order" => 3,"isFree"=> false],
                        ],
                    ],
                    [
                        "order" => 2,
                        "title" => "Bases de Datos y Eloquent",
                        "isExpanded" => false,
                        "lessons" => [
                            ["id" => 4, "title" => "2.1 Migraciones", "duration" => "15m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 5, "title" => "2.2 Relaciones", "duration" => "20m", "type" => "video", "order" => 2,"isFree"=> false],
                            ["id" => 6, "title" => "2.3 Query Builder vs Eloquent", "duration" => "18m", "type" => "markdown", "order" => 3,"isFree"=> false],
                        ],
                    ],
                    [
                        "order" => 3,
                        "title" => "APIs y Seguridad",
                        "isExpanded" => false,
                        "lessons" => [
                            ["id" => 7, "title" => "3.1 API Resources", "duration" => "20m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 8, "title" => "3.2 Autenticación con Sanctum", "duration" => "25m", "type" => "video", "order" => 2,"isFree"=> false],
                            ["id" => 9, "title" => "3.3 Rate Limits y Seguridad", "duration" => "18m", "type" => "video", "order" => 3,"isFree"=> false],
                        ],
                    ],
                ]),
                'autor' => 'Chris Gámez'
            ],

            /* ============================
       CURSO 3
    ============================= */
            [
                'orden' => 3,
                'titulo' => 'Tailwind CSS Profesional',
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/d/d5/Tailwind_CSS_Logo.svg',
                'descripcion' => 'Crea interfaces modernas, responsivas y optimizadas usando Tailwind CSS desde cero.',
                'nivel' => 'Principiante',
                'categoria' => 'Frontend',
                'vistas' => 0,
                'precio_actual' => 9.99,
                'url_video_intro' => 'https://www.dailymotion.com/embed/video/k4WljjJFB66A4eDKHaK?autoplay=1&ui-logo=0&sharing-enable=0&queue-enable=0',
                'precio_anterior' => 49.99,
                'duracion' => '2 Horas',
                'contenido' => json_encode([
                    [
                        "order" => 1,
                        "title" => "Introducción a Tailwind",
                        "isExpanded" => true,
                        "lessons" => [
                            ["id" => 10, "title" => "1.1 ¿Qué es Tailwind?", "duration" => "6m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 11, "title" => "1.2 Configuración Inicial", "duration" => "12m", "type" => "video", "order" => 2,"isFree"=> false],
                        ],
                    ],
                    [
                        "order" => 2,
                        "title" => "Layouts y Responsive",
                        "isExpanded" => false,
                        "lessons" => [
                            ["id" => 12, "title" => "2.1 Flexbox y Grid", "duration" => "20m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 13, "title" => "2.2 Breakpoints", "duration" => "14m", "type" => "video", "order" => 2,"isFree"=> false],
                        ],
                    ],
                    [
                        "order" => 3,
                        "title" => "Componentes y Buenas Prácticas",
                        "isExpanded" => false,
                        "lessons" => [
                            ["id" => 14, "title" => "3.1 Componentes Reutilizables", "duration" => "18m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 15, "title" => "3.2 Optimización", "duration" => "12m", "type" => "video", "order" => 2,"isFree"=> false],
                        ],
                    ],
                ]),
                'autor' => 'Chris Gámez'
            ],

            /* ============================
       CURSO 4
    ============================= */
            [
                'orden' => 4,
                'titulo' => 'Node.js y Express API PRO',
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/d/d9/Node.js_logo.svg',
                'descripcion' => 'Aprende a construir APIs modernas con Node.js, Express, JWT, Docker y pruebas unitarias.',
                'nivel' => 'Intermedio',
                'categoria' => 'Backend',
                'vistas' => 0,
                'precio_actual' => 12.99,
                'precio_anterior' => 69.99,
                'url_video_intro' => 'https://www.dailymotion.com/embed/video/k4WljjJFB66A4eDKHaK?autoplay=1&ui-logo=0&sharing-enable=0&queue-enable=0',
                'duracion' => '3 Horas',
                'contenido' => json_encode([
                    [
                        "order" => 1,
                        "title" => "Fundamentos de Express",
                        "isExpanded" => true,
                        "lessons" => [
                            ["id" => 20, "title" => "1.1 Router Básico", "duration" => "10m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 21, "title" => "1.2 Middlewares", "duration" => "13m", "type" => "video", "order" => 2,"isFree"=> false],
                        ],
                    ],
                    [
                        "order" => 2,
                        "title" => "APIs REST Pro",
                        "isExpanded" => false,
                        "lessons" => [
                            ["id" => 22, "title" => "2.1 Controladores y Servicios", "duration" => "18m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 23, "title" => "2.2 Validación con Joi", "duration" => "15m", "type" => "video", "order" => 2,"isFree"=> false],
                        ],
                    ],
                    [
                        "order" => 3,
                        "title" => "Seguridad y Despliegue",
                        "isExpanded" => false,
                        "lessons" => [
                            ["id" => 24, "title" => "3.1 JWT", "duration" => "20m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 25, "title" => "3.2 Dockerización", "duration" => "25m", "type" => "video", "order" => 2,"isFree"=> false],
                        ],
                    ],
                ]),
                'autor' => 'Chris Gámez'
            ],

            /* ============================
       CURSO 5
    ============================= */
            [
                'orden' => 5,
                'titulo' => 'DynamoDB y Serverless Real',
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/8/8c/Amazon_DynamoDB_logo.svg',
                'descripcion' => 'Aprende DynamoDB con ejemplos reales, indexación, páginas, consultas y arquitectura Serverless.',
                'nivel' => 'Intermedio',
                'categoria' => 'Cloud',
                'vistas' => 0,
                'precio_actual' => 19.99,
                'precio_anterior' => 89.99,
                'url_video_intro' => 'https://www.dailymotion.com/embed/video/k4WljjJFB66A4eDKHaK?autoplay=1&ui-logo=0&sharing-enable=0&queue-enable=0',
                'duracion' => '4 Horas',
                'contenido' => json_encode([
                    [
                        "order" => 1,
                        "title" => "Fundamentos NoSQL",
                        "isExpanded" => true,
                        "lessons" => [
                            ["id" => 26, "title" => "1.1 Conceptos NoSQL", "duration" => "10m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 27, "title" => "1.2 Modelado DynamoDB", "duration" => "18m", "type" => "video", "order" => 2,"isFree"=> false],
                        ],
                    ],
                    [
                        "order" => 2,
                        "title" => "Consultas Avanzadas",
                        "isExpanded" => false,
                        "lessons" => [
                            ["id" => 28, "title" => "2.1 Query y Scan", "duration" => "22m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 29, "title" => "2.2 GSI y LSI", "duration" => "20m", "type" => "video", "order" => 2,"isFree"=> false],
                        ],
                    ],
                    [
                        "order" => 3,
                        "title" => "Serverless",
                        "isExpanded" => false,
                        "lessons" => [
                            ["id" => 30, "title" => "3.1 Lambda + DynamoDB", "duration" => "25m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 31, "title" => "3.2 Arquitecturas Event-Driven", "duration" => "30m", "type" => "video", "order" => 2,"isFree"=> false],
                        ],
                    ],
                ]),
                'autor' => 'Chris Gámez'
            ],

            /* ============================
       CURSO 6
    ============================= */
            [
                'orden' => 6,
                'titulo' => 'Git y GitHub para Equipos',
                'imagen' => 'https://upload.wikimedia.org/wikipedia/commons/9/91/Octicons-mark-github.svg',
                'descripcion' => 'Aprende Git desde cero, ramas, merges, conflictos, GitFlow y trabajo colaborativo en GitHub.',
                'nivel' => 'Principiante',
                'categoria' => 'DevOps',
                'vistas' => 0,
                'precio_actual' => 7.99,
                'precio_anterior' => 39.99,
                'url_video_intro' => 'https://www.dailymotion.com/embed/video/k4WljjJFB66A4eDKHaK?autoplay=1&ui-logo=0&sharing-enable=0&queue-enable=0',
                'duracion' => '2 Horas',
                'contenido' => json_encode([
                    [
                        "order" => 1,
                        "title" => "Fundamentos de Control de Versiones",
                        "isExpanded" => true,
                        "lessons" => [
                            ["id" => 32, "title" => "1.1 Qué es Git", "duration" => "5m", "type" => "video", "order" => 1,"isFree"=> true],
                            ["id" => 33, "title" => "1.2 Commits y Estado", "duration" => "12m", "type" => "video", "order" => 2,"isFree"=> true],
                        ],
                    ],
                    [
                        "order" => 2,
                        "title" => "Trabajo con Ramas",
                        "isExpanded" => false,
                        "lessons" => [
                            ["id" => 34, "title" => "2.1 Branching", "duration" => "15m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 35, "title" => "2.2 Merge y Rebase", "duration" => "18m", "type" => "video", "order" => 2,"isFree"=> false],
                        ],
                    ],
                    [
                        "order" => 3,
                        "title" => "GitHub Profesional",
                        "isExpanded" => false,
                        "lessons" => [
                            ["id" => 36, "title" => "3.1 Pull Requests", "duration" => "20m", "type" => "video", "order" => 1,"isFree"=> false],
                            ["id" => 37, "title" => "3.2 Automatización con GitHub Actions", "duration" => "25m", "type" => "video", "order" => 2,"isFree"=> false],
                        ],
                    ],
                ]),
                'autor' => 'Chris Gámez'
            ],
        ]);
        //crea 5 cursos mas

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
