# 🐘 PHPZone – Panel de Gestión de Archivos con AJAX

**PHPZone** es una plataforma ligera y moderna desarrollada en **PHP**, **JavaScript (AJAX)** y **CSS puro**, pensada para gestionar archivos de forma rápida, segura y sin recargar la página.  
El proyecto combina un backend sencillo pero robusto con una interfaz limpia inspirada en el entorno PHP, utilizando el clásico **azul corporativo** de su identidad visual.

---

## 🚀 Funcionalidades principales

- 🔹 **Autenticación de usuario y control de sesión** con expiración por inactividad.  
- 🧩 **Subida de archivos vía Drag & Drop** con vista previa instantánea y barra de progreso dinámica.  
- ⚡ **Carga asíncrona de archivos (AJAX)** — sin recargar la página en ningún momento.  
- 🧾 **Listado dinámico** con paginación automática y buscador instantáneo.  
- 🗑️ **Eliminación individual o total** de archivos mediante peticiones AJAX.  
- 📦 **Diseño responsive** y optimizado para escritorio y móvil.  
- 💬 **Toasts animados y modales** para notificaciones y mensajes de error.  
- 🎨 **Interfaz moderna y coherente** con el estilo visual del ecosistema PHP (azul limpio, sombras suaves y tipografía “Nunito Sans”).

---

## 🧱 Tecnologías utilizadas

| Tecnología | Uso principal |
|-------------|----------------|
| **PHP 8+** | Backend y gestión de archivos (subir, listar, borrar). |
| **JavaScript (ES6)** | Peticiones AJAX, UI dinámica, drag & drop y notificaciones. |
| **HTML5 / CSS3** | Interfaz responsive y limpia (sin frameworks externos). |
| **Font Awesome 6** | Iconografía consistente y moderna. |

---

## 📂 Estructura del proyecto

📦 PHPZone
┣ 📂 public/
┃ ┣ 📂 css/
┃ ┃ ┣ estilos.css
┃ ┃ ┣ layout.css
┃ ┃ ┗ extras.css
┃ ┗ 📂 js/
┃ ┣ main.js
┃ ┣ layout.js
┃ ┗ header.js
┣ 📂 php/
┃ ┣ subirArchivo.php
┃ ┣ listarArchivos.php
┃ ┣ borrarArchivo.php
┃ ┗ borrarTodo.php
┣ 📂 components/
┃ ┗ header.php
┣ 📂 uploads/
┃ ┗ (archivos subidos)
┣ index.php
┣ dashboard.php
┗ README.md

yaml
Copiar código

---

## 🧠 Flujo general

1. El usuario inicia sesión y accede al **panel de control**.  
2. Arrastra o selecciona un archivo y define su **nombre visible**.  
3. Se muestra el progreso de subida en tiempo real.  
4. El archivo aparece inmediatamente en la tabla con sus datos (tipo, tamaño, fecha).  
5. Desde ahí puede descargarse o eliminarse sin recargar la página.

---

## 🧑‍💻 Autor

**Asier Cobas**  
Desarrollador Web Full Stack  
💙 Proyecto desarrollado con cariño para la comunidad PHP.  

---

## 🐘 Licencia

Este proyecto está licenciado bajo la **MIT License**.  
Puedes usarlo libremente para fines personales o profesionales, siempre que mantengas el crédito original.
