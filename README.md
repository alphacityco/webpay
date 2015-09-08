# webpay
Prueba de los KCC de Webpay en Digitalocean

El propósito de este repositorio es tener una instalación básica de Woocomerce/Webpay que se pueda clonar y hacer funcionar con la menor cantidad de inconvenientes posibles. 

La primera prueba realizada usando el <a href="https://bitbucket.org/ctala/webpayconector/src/" target="_blank">WebPayConector</a> de <a href="https://www.cristiantala.cl/" target="_blank">Cristian Tala Sánchez</a> se hizo sobre un <b>droplet de DigitalOcean</b> con las siguientes características: 

  *   Ubuntu 14 x86_64 x86_64 x86_64
  *   Apache2 con soporte para la ejecución de  cgis:
      Editar /etc/apache2/sites-enabled/000-default.conf
      Agregar las líneas:     (Para que el directorio esté dentro del public del server)
       ```
       ScriptAlias /cgi-bin/ /var/www/html/cgi-bin/
      
        <Directory "/var/www/html/cgi-bin">
                AllowOverride None
                Options +ExecCGI -MultiViews +SymLinksIfOwnerMatch
                Order allow,deny
                Allow from all
        </Directory>
      
      ``` 
      Agregar el módulo para apache:  a2enmod cgi
      Crear el directorio: mkdir /var/www/html/cgi-bin
      Asignarlo al usuario de apache: chown www-data:www-data cgi-bin
      Darle permisos 775: chmod 755 cgi-bin
      Reiniciar apache: service apache2 restart

Para evitar el problema que da subir el kcc de Webpay por FTP (hay que subirlos en binario zipeados, descomprimirlos en zip), me los descargué directamente desde Transbank:
* wget https://www.transbank.cl/public/files/kits/kcc-6.0.2-linux-64.rar
* Descomprimirlos por ejemplo en /root/webpay (apt-get install unrar-free)
* Copiar los archivos al directorio cgi-bin: cp -r kcc 6.0.2 linux 64/cgi-bin/* /var/www/html/cgi-bin/
* Cambiar usuario de root a www-data y otorgarle permisos 755 a /var/www/html/cgi-bin/* <b>los programas deben tener los permisos adecuados y pertenecer al usuario grupo de apache para funcionar</b>
                              
Al ingresar en la URL (su ip en vez de 45.55..) http://45.55.88.47/cgi-bin/tbk_bp_pago.cgi

Editar el archivo /var/www/html/cgi-bin/tbk_check_mac.cgi con los valores propios 

```
IDCOMERCIO = 597026007976
MEDCOM = 2
TBK_KEY_ID = 101
PARAMVERIFCOM = 1
URLCGICOM = http://45.55.88.47/cgi-bin/tbk_bp_resultado.cgi
SERVERCOM = 45.55.88.47
PORTCOM = 80
WHITELISTCOM = ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz 0123456789./:=&?_
HOST = 45.55.88.47
WPORT = 80
URLCGITRA = /filtroUnificado/bp_revision.cgi
URLCGIMEDTRA = /filtroUnificado/bp_validacion.cgi
SERVERTRA = https://certificacion.webpay.cl
PORTTRA = 6443
PREFIJO_CONF_TR = HTML_
HTML_TR_NORMAL = http://45.55.88.47/xt_compra.php
```

<b>*** Debe ver el siguiente error: </b>

<img src="https://raw.githubusercontent.com/clsource/guia-webpay/master/webpay-kcc/img/1/fig09.png" style="max-width:100%;">

Sí, aunque usted no lo crea, lo mejor que le puede pasar hasta este momento durante la instalación de los cgis de Webpay es ver ese error. Así que, disfrútelo y dese ánimos para continugar. 

# Archivos php del WebPayConector: 
* index.php
* xt_compra.php
* fracaso.php
* exito.php

Crear el directorio "comun" en /var/www/html/ (debe quedar así: /var/www/html/comun/) y darle permisos 755 y asignarle el usuario:grupo www-data para que los phps puedan escribir en él. 

Abrir TODOS, uno por uno, y editar/cambiar todos los valores en las variables locales para que funcionen... 

# index.php

```
/* * **************** CONFIGURACION ****************** */
$TBK_TIPO_TRANSACCION = "TR_NORMAL";
$TBK_URL_EXITO
        = "http://45.55.88.47/exito.php";
$TBK_URL_FRACASO
        = "http://45.55.88.47/fracaso.php";
$url_cgi
        = "http://45.55.88.47/cgi-bin/tbk_bp_pago.cgi";
//Archivos de datos para uso de pagina de cierre
$myPath
        =
        "/var/www/html/comun/dato$TBK_ID_SESION.log";
/* * **************** FIN CONFIGURACION **************** */
```

# xt_compra.php

```

/* * **************** CONFIGURAR AQUI ****************** */
$myPath
        = "/var/www/html/comun/dato$TBK_ID_SESION.log";
//GENERA ARCHIVO PARA MAC
$filename_txt
        = "/var/www/html/comun/MAC01Normal$TBK_ID_SESION.txt";
// Ruta Checkmac
$cmdline
        = "/var/www/html/cgi-bin/tbk_check_mac.cgi $filename_txt";
/* * **************** FIN CONFIGURACION **************** */

```

# exito.php

```
/* * **************** CONFIGURAR AQUI ****************** */
$myPath
        =
        "/var/www/html/comun/MAC01Normal$TBK_ID_SESION.txt";
$pathSubmit
        = "http://45.55.88.47/index.php";
/* * **************** FIN CONFIGURACION **************** */

```

# fracaso.php
```
/* * **************** CONFIGURAR AQUI ****************** */
$PATHSUBMIT
        = "http://45.55.88.47/index.php";
/* * **************** FIN CONFIGURACION **************** */

```
# Código php para saber si EXEC() está habilitado y obtener el fullpath donde se ejecutan los programas
```
<?php 
 if(exec('echo EXEC') == 'EXEC'){
     echo 'exec est&aacute; habilitado <br><br>';
 }
 echo "<b>Fulpath:</b> ". getcwd() . "\n";
?>
```

# Más allá del error 46

Si todo sale bien, antes de volver al sitio con el fatídico "failure" en la url, deberán ver esta gloriosa pantalla: 

<img src="http://i.imgur.com/q9TGBkT.jpg">

# Pruebas adicionales

Estos php utilizan la función de "exec" habitualmente deshabilitada en los hosting comunes. Algunos la activan bajo petición pero la mayoría, no... Ahora bien, como la meta es hacer funcionar estos cgis antes de trastear con el plugin para Woocomcer del señor Tala, (ver: <a href="http://www.cristiantala.cl/como-crear-un-ecommerce-en-chile-en-5-minutos/" target="_blank">Como crear un eCommerce en Chile en 5 minutos</a>), lo próximo será descartar que funcionen con el EXEC deshabilitado (ojalá esté hecho con Curl) para evitar problemas cuando vayamos a subir esto finalmente en un hosting. 

Llegado a este punto, es muy útil tener en cuenta que los KCC no son la única forma de integrar Webpay, también se puede hacer vía SOAP e invirtiendo un poco, se puede fascilitar un mundo la integración usando el <a href="http://www.cristiantala.cl/producto/pack-de-5-licencias-para-el-plugin-de-webpay-webservice/" target="_blank">plugin de Webpay WebService</a> que también desarrolla el señor Tala. 

# Otra guía para consultar

<a href="https://github.com/clsource/guia-webpay/tree/master/webpay-kcc" target="_blank">Guía de instalación WebPay KCC en Linux</a>
