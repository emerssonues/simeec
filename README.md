SIS
========================
SIS es una plantilla que utiliza las versiones mas actualizadas de Symfony en su version
2.4 y Sonata con sus respectivos bunldes, la idea de este proyecto es facilitar la
actulizacion de Symfony y Sonata a versiones posteriores, permitiendo con ello crear
una estructura que permita integrar o actualizar los sistemas de verisones antiguas
a una version mas reciente.

Instalacion del Sistema
========================

Esta documentacion contiene informacion de como descargar, instalar el SIS.

1) Clonar el proyecto del repositorio
--------------------------------------

    git clone git@<servidor>:<directorio>/sis.git

### Uso de Composer (*recomendado*)

SIS utiliza Composer para manejar las dependencias (bundles) que son utilizados dentro del proyecto.

Si no se tiene composer instalado utilizar el siguiente comando dentro del directorio sisbl:

    curl -s http://getcomposer.org/installer | php

2) Configuracion de la base de datos
-------------------------------------
Creacion del usuario de la base de datos como usuario **postgres** ejecutar:

    createuser -DRSP sis

Creacion de la base de datos del sistema

    createdb sis -O sis

3) Configuracion del SIS
-------------------------------
Todos los pasos que se describen a continuación deben realizarse como usuario normal.

Creacion de las carpetas cache y logs: 

    mkdir -p app/cache app/logs

Brindando permisos a apache de escribir en dichas carpetas

    setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache/ app/logs/
    setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache/ app/logs/

crear el archivo **parameters.yml** dentro directorio **app/config/** con el siguiente contenido

    parameters:
        database_driver: pdo_pgsql
        database_host: 127.0.0.1
        database_port: '5432'
        database_name: name
        database_user: user
        database_password: password
        mailer_transport: smtp
        mailer_host: 127.0.0.1
        mailer_user: null
        mailer_password: null
        locale: es
        secret: 3510ed099082e9d9616ce16639ab538a6

Cambiar los valores de **database_name, database_user, database_password** por los valores apropiados.

4) Instalacion de las dependencias (Bundles)
--------------------------------------------

Usar el siguiente comando como usuario normal:

    php composer.phar install

5) Realizando Virtual Host
--------------------------------------------
Como **usuario root** ejecutar:
        grep NameVirtualHost /etc/apache2/ports.conf

Aparecerá algo similar a lo siguiente:

 1. NameVirtualHost 192.168.1.1:80
 2. NameVirtualHost *:80

El caso 1 indica que NameVirtualHost está configurado en la IP 192.168.1.1 en
el puerto 80. Para el caso 2, NameVirtualHost está configurado para todas las 
direcciones IP configuradas en el servidor en el puerto 80. Esta dirección IP
se repetirá en cada VirtualHost que se cree. Por lo que es importante colocar
el valor indicado.

Como **usuario root**, moverse a la carpeta:

        cd /etc/apache2/sites-available/

Con un editor de texto crear el archivo **sis.localhost** con el siguiente contenido:

        # Inicio del archivo
        <VirtualHost VARIABLE_RETORNADA>
        ServerName sis.localhost
        DocumentRoot /var/www/sis/web  ##Esta debe ser la ruta donde está el proyecto y se debe de borrar este comentario
        DirectoryIndex app.php
        <Directory /var/www/sis/web >  ##Esta debe ser la ruta donde está el proyecto y se debe de borrar este comentario
                Options FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>
        ErrorLog ${APACHE_LOG_DIR}/sis.localhost-error.log
        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn
        CustomLog ${APACHE_LOG_DIR}/sis.localhost-access.log combined
        </VirtualHost>
        # Fin del archivo

Guardar el archivo. Luego, como **root** ejecutar:

        a2ensite sis.localhost

Habilitar el modo de reescritura con la siguiente sentencia:

        a2enmod rewrite

Reiniciar el servicio de Apache

        /etc/init.d/apache2 restart

Se debe agregar en el archivo **/etc/hosts** la IP junto con el ServerName 
del Virtual Host. La línea debe ser similar a la siguiente:

        X.X.X.X       sis.localhost


6) Verificando la configuracion del Sistema
--------------------------------------------

Antes de empezar hay que asegurarse que el sistema local esta configurado correctamente para Symfony y sus dependencias.

Acceder al script **config.php** desde el navegador:

    http://sis.localhost/config.php

Si se obtienen warnings o recomendaciones, corregirlas antes de empezar a utilizar el sistema.

6) Actualizacion de la base de datos
--------------------------------------------
Actualizar el esquema de la base de datos

    php app/console doctrine:schema:update --force

7) Creacion del usuario dentro del sistema
--------------------------------------------
Crear el usuario que tendra los privilegios de SUPER ADMIN y tendra acceso total al sistema.

    php app/console fos:user:create --super-admin

Limpiar la cache del sistema:

    php app/console cache:clear

Actualizar los assets

    php app/console assets:install web --symlink

Ingresar al sistema.

    http://sis.localhost/app_dev.php/

Documentacion Oficial
-------------------------------

  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][6] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][7] - Adds support for the Doctrine ORM

  * [**TwigBundle**][8] - Adds support for the Twig templating engine

  * [**SecurityBundle**][9] - Adds security by integrating Symfony's security
    component

  * [**SwiftmailerBundle**][10] - Adds support for Swiftmailer, a library for
    sending emails

  * [**MonologBundle**][11] - Adds support for Monolog, a logging library

  * [**AsseticBundle**][12] - Adds support for Assetic, an asset processing
    library

  * [**JMSSecurityExtraBundle**][13] - Allows security to be added via
    annotations

  * [**JMSDiExtraBundle**][14] - Adds more powerful dependency injection
    features

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][15] (in dev/test env) - Adds code generation
    capabilities

  * **AcmeDemoBundle** (in dev/test env) - A demo bundle with some example
    code

[1]:  http://symfony.com/doc/2.4/book/installation.html
[2]:  http://getcomposer.org/
[3]:  http://symfony.com/download
[4]:  http://symfony.com/doc/2.4/quick_tour/the_big_picture.html
[5]:  http://symfony.com/doc/2.4/index.html
[6]:  http://symfony.com/doc/2.4/bundles/SensioFrameworkExtraBundle/index.html
[7]:  http://symfony.com/doc/2.4/book/doctrine.html
[8]:  http://symfony.com/doc/2.4/book/templating.html
[9]:  http://symfony.com/doc/2.4/book/security.html
[10]: http://symfony.com/doc/2.4/cookbook/email.html
[11]: http://symfony.com/doc/2.4/cookbook/logging/monolog.html
[12]: http://symfony.com/doc/2.4/cookbook/assetic/asset_management.html
[13]: http://symfony.com/doc/2.4/bundles/SensioGeneratorBundle/index.html
