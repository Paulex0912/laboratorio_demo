---
name: Security Review
description: Guía y procedimientos para realizar revisiones de código y de seguridad en la aplicación Laravel.
---

# Revisión de Seguridad (Security Review Skill)

Esta habilidad documenta los pasos organizados y las mejores prácticas para auditar la seguridad en el proyecto actual de Laravel. Se recomienda utilizar esta guía antes de enviar características críticas a producción.

## 1. Análisis de Dependencias y Entorno
- **Auditoría de dependencias en PHP:** Ejecutar `composer audit` o revisar manualmente los paquetes definidos en `composer.json` frente a vulnerabilidades conocidas.
- **Auditoría de paquetes NPM:** Revisar `package.json` utilizando herramientas como `npm audit`.
- **Variables de Entorno (.env):** 
  - Asegurar de que la clave `APP_DEBUG=true` no se despliegue en entornos de producción.
  - Verificar que el archivo `.env` se encuentra correctamente respaldado pero siempre en el `.gitignore`.

## 2. Inyección SQL (SQLi)
- Revisar todo uso de la fachada `DB`. El uso de `DB::raw()`, `whereRaw()`, o `orderByRaw()` es especialmente riesgoso si se concatena directamente la entrada del usuario (`$request->input()`).
- Fomentar el uso de Eloquent ORM. Siempre que sea posible, utilizar parámetros vinculados u ORM regular.

## 3. Cross-Site Scripting (XSS)
- **Vistas en Blade:** Inspeccionar el uso de `{!! $variable !!}`. Las etiquetas de datos no procesadas deben estar aseguradas y provenir siempre de fuentes confiables (o haber pasado previamente por bibliotecas de limpieza o `strip_tags`).
- Siempre se prefiere el escapar los datos usando la sintaxis normal de Blade: `{{ $variable }}`.

## 4. Cross-Site Request Forgery (CSRF)
- Las rutas web (que no pertenecen a una API REST sin estado) deben estar protegidas usando el intermediario proporcionado por Laravel y agregar la directiva `@csrf` en los formularios donde el método sea distinto de GET.

## 5. Autenticación y Autorización de Acceso
- **Rutas Prohibidas:** Verificar rigurosamente el middleware de las rutas sensibles (`auth`, `role`, `permission`).
- **Control de Acceso (IDOR):** Revisar que en las solicitudes (por ejemplo, buscar, editar o eliminar modelos por ID), el usuario actual tenga el rol necesario o el propietario correcto de ese registro (ej., mediante `Policies`). 
- **Roles:** Inspeccionar si la lógica `CheckRole` se aplica correctamente en los controladores críticos.

## 6. Validación de Entradas
- Nunca confiar implícitamente en los datos del usuario. Toda la información enviada mediante `POST/PUT/PATCH` o variables de consulta debe someterse explícitamente a un procesamiento mediante `Form Requests` o la validación del controlador (`$request->validate()`). 
- Utilizar fuertes validaciones en atributos, por ejemplo: `email`, `numeric`, limitación de longitud (`max:255`), etc.

## 7. Carga de Archivos
- Validar las extensiones, tamaño de los archivos, y los tipos de contenido MIME (`mimes:jpg,png,pdf` o `image`).
- Manejar de forma segura las ubicaciones de carga predeterminadas; no depositar archivos sin verificar dentro del directorio base `/public`.

## Acciones sugeridas al usar esta habilidad (Prompts / Comandos útiles)
- Ejecutar `php artisan route:list` para identificar si hay rutas sensibles desprotegidas.
- Usar `grep_search` buscando:
  - `DB::raw`
  - `{!!`
  - `->validate()`
  - `$request->all()`
