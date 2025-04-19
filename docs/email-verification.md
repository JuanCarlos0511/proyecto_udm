# Sistema de Verificación por Correo Electrónico

Este documento describe la implementación del sistema de verificación por correo electrónico para el registro de usuarios en Clínica Miel.

## Características

- Registro de usuarios con verificación por correo electrónico
- Envío de código de verificación de 6 dígitos
- Página de verificación con opción para reenviar el código
- Expiración automática del código después de 30 minutos
- Redirección automática al perfil después de la verificación exitosa

## Configuración del Correo Electrónico

Para que el sistema de verificación funcione correctamente, es necesario configurar el envío de correos electrónicos en el archivo `.env`:

### Para Desarrollo (Mailtrap)

[Mailtrap](https://mailtrap.io/) es un servicio que captura los correos enviados por la aplicación sin enviarlos realmente a los destinatarios, ideal para desarrollo y pruebas.

```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@clinicamiel.com"
MAIL_FROM_NAME="Clínica Miel"
```

### Para Producción (Gmail)

Para usar Gmail como servicio de correo en producción:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tucorreo@gmail.com
MAIL_PASSWORD=tu_password_o_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@clinicamiel.com"
MAIL_FROM_NAME="Clínica Miel"
```

> **Nota**: Para Gmail, es recomendable usar una "Contraseña de aplicación" en lugar de la contraseña normal de la cuenta. Puedes generar una en la configuración de seguridad de tu cuenta de Google.

## Flujo de Registro

1. El usuario completa el formulario de registro con su correo electrónico y contraseña
2. El sistema genera un código de verificación de 6 dígitos
3. El sistema envía el código al correo electrónico del usuario
4. El usuario es redirigido a la página de verificación
5. El usuario ingresa el código recibido
6. Si el código es correcto, se crea la cuenta y el usuario es redirigido al perfil
7. Si el código es incorrecto, se muestra un mensaje de error

## Archivos Implementados

- **Controlador**: `app/Http/Controllers/Auth/RegisterController.php`
- **Clase de Correo**: `app/Mail/VerificationCode.php`
- **Plantilla de Correo**: `resources/views/emails/verification-code.blade.php`
- **Vista de Verificación**: `resources/views/auth/verify.blade.php`
- **Estilos CSS**: `public/css/pages/verify.css`

## Rutas

```php
// Rutas de registro y verificación
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::get('/verify', [RegisterController::class, 'showVerificationForm'])->name('verification.show');
Route::post('/verify', [RegisterController::class, 'verify'])->name('verification.verify');
Route::get('/verify/resend', [RegisterController::class, 'resendCode'])->name('verification.resend');
```

## Personalización

### Plantilla de Correo

La plantilla del correo electrónico se encuentra en `resources/views/emails/verification-code.blade.php`. Puedes personalizarla para adaptarla al diseño de la aplicación.

### Tiempo de Expiración

Por defecto, el código de verificación expira después de 30 minutos. Puedes modificar este tiempo en el método `register` del controlador:

```php
'expires_at' => now()->addMinutes(30), // Cambia 30 por el número de minutos deseado
```

### Longitud del Código

Por defecto, el código de verificación tiene 6 dígitos. Puedes modificar la longitud en el método `register` del controlador:

```php
$verificationCode = rand(100000, 999999); // Para 6 dígitos
// o
$verificationCode = rand(1000, 9999); // Para 4 dígitos
```

## Consideraciones de Seguridad

- Los códigos de verificación expiran después de un tiempo determinado
- Los datos de registro se almacenan temporalmente en la sesión
- Se verifica que el correo electrónico no esté ya registrado
- Se requiere una contraseña de al menos 8 caracteres
