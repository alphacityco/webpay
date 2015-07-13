<?PHP
/* * **************** CONFIGURAR AQUI ****************** */
$PATHSUBMIT
        = "http://45.55.88.47/index.php";
/* * **************** FIN CONFIGURACION **************** */
$TBK_ID_SESION
        = $_POST["TBK_ID_SESION"];
$TBK_ORDEN_COMPRA
        = $_POST["TBK_ORDEN_COMPRA"];
?>
<HTML>
    <HEAD><TITLE>TIENDA PHP KCC6.0</TITLE></HEAD>
    <BODY>
    <CENTER>
        <B>TRANSACCIÓN FRACASADA !!!</B>
        <TABLE>
            <TR><TH>FRACASO</TH></TR>
            <TR><TD>
                    TBK_ID_SESION=<?PHP ECHO $TBK_ID_SESION; ?><BR>
                    TBK_ORDEN_COMPRA=<?PHP ECHO $TBK_ORDEN_COMPRA; ?><BR>
                </TD></TR>
        </TABLE>
    </CENTER>
    <FORM ACTION="<?PHP ECHO $PATHSUBMIT; ?>" METHOD=POST>
        <CENTER>
            <INPUT TYPE="SUBMIT" VALUE="VOLVER A INICIO">
        </CENTER>
    </FORM>
</BODY>
</HTML>
