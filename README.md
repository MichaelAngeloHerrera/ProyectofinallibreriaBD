# BookManager - Sistema de Gestión de Librería

Aplicación en **PHP 8 orientado a objetos** + **MySQL** (PDO con sentencias preparadas) + **Bootstrap 5**
para gestionar tiendas y títulos (libros) de una librería.

## Estructura

```
bookmanager/
├── assets/            CSS y JS del dashboard
├── bd/                Scripts SQL (local e InfinityFree)
├── config/database.php   Clase Database (conexión PDO)
├── models/
│   ├── Tienda.php     CRUD de tiendas
│   └── Titulo.php     CRUD de títulos (+ getPublicadores para el <select>)
├── views/
│   ├── tiendas/       index, crear, editar, eliminar (+ _form)
│   └── titulos/       index, crear, editar, eliminar (+ _form)
├── includes/          header (menú lateral), footer, funciones
└── index.php          Inicio del dashboard
```

## Arquitectura POO (para el documento)

- **Database** (`config/database.php`): encapsula la conexión PDO. Las credenciales son propiedades
  privadas y `getConnection()` devuelve una instancia reutilizable de PDO con excepciones activadas.
- **Modelos** (`Tienda`, `Titulo`): reciben el PDO por el constructor (inyección de dependencias),
  tienen propiedades privadas, getters, validación interna (`validar()`) y los métodos
  `getAll()`, `getById()`, `create()`, `update()` y `delete()`. Todo el SQL usa `prepare()` + parámetros.
- **Vistas**: cada vista instancia el modelo, pide los datos y los pinta con Bootstrap. Los formularios
  usan POST y redirigen con `header("Location: ...")` (patrón Post/Redirect/Get). Los errores de
  validación se muestran en el formulario y los mensajes de éxito con mensajes flash en sesión.
- **Seguridad**: sentencias preparadas contra inyección SQL y `htmlspecialchars` (función `e()`) contra XSS;
  la eliminación se ejecuta solo por POST después de una pantalla de confirmación.

## Instalación local (XAMPP)

1. Instala XAMPP en `C:\xampp` e inicia **Apache** y **MySQL** en el panel de control.
2. Copia la carpeta `bookmanager` a `C:\xampp\htdocs\`.
3. Abre `http://localhost/phpmyadmin` > pestaña **Importar** > elige `bd/db_libreria.sql` > **Importar**.
4. Abre `http://localhost/bookmanager/`.

Credenciales por defecto de XAMPP: usuario `root`, sin contraseña (ya configuradas en `config/database.php`).

## Publicar en InfinityFree

1. Regístrate en https://www.infinityfree.com y crea una cuenta de hosting con un subdominio gratuito.
2. En el panel, abre **MySQL Databases**, crea una base y anota: **host** (ej. `sql123.infinityfree.com`),
   **nombre de la base**, **usuario** y **contraseña**.
3. Abre **phpMyAdmin** desde el panel, selecciona esa base y en **Importar** sube
   **`bd/db_libreria_infinityfree.sql`** (este archivo no intenta crear la base, que InfinityFree no permite).
4. Edita `config/database.php` y cambia las 4 variables:
   ```php
   private string $host   = 'sql123.infinityfree.com';
   private string $dbname = 'epiz_12345678_dblibreria';
   private string $user   = 'epiz_12345678';
   private string $pass   = 'tu_contraseña_remota';
   ```
5. En el **Online File Manager** (o con FileZilla), entra a la carpeta `htdocs` y sube todo el proyecto
   (assets, config, models, views, includes, index.php). Si quieres que la URL sea la raíz del sitio,
   sube el **contenido** de `bookmanager` directamente dentro de `htdocs`.
6. Visita tu URL pública, entra a Tiendas y Títulos y crea un registro para comprobar que escribe en la base remota.

## Subir el código a GitHub

> Antes de subir, **no dejes contraseñas reales** en `config/database.php` (déjalo con los valores locales
> y cambia las credenciales remotas solo en la copia que subes a InfinityFree).

```bash
cd bookmanager
git init
git add .
git commit -m "BookManager: CRUD de tiendas y títulos"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/bookmanager.git
git push -u origin main
```

## Documento PDF de entrega (2-3 páginas)

Debe incluir: capturas del panel de InfinityFree (hosting y MySQL Databases), la URL pública del
proyecto y una explicación breve de la arquitectura POO (puedes basarte en la sección de arriba).
