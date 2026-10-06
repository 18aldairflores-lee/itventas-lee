<?php 
require_once "../modelos/Permiso.php";

$permiso = new Permiso();

switch ($_GET["op"]) {
    case 'listar':
        $rspta = $permiso->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<span class="badge-cat" style="font-weight:700;"># ' . $reg->idpermiso . '</span>',
                "1" => '<strong><i class="fa fa-key text-primary" style="margin-right:8px;"></i>' . $reg->nombre . '</strong>',
                "2" => '<span class="badge-pill-modern badge-pill-success"><span class="dot-indicator"></span> Habilitado</span>'
            );
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;
}
?>