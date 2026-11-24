<?php
// Envío del formulario de cotización (hosting con PHP, p. ej. DonWeb).
// Ajustar $para y probar el envío antes de publicar.
$para = 'ventas.aceroselizalde@gmail.com';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !empty($_POST['web'])) { http_response_code(400); exit; }
function limpio($v){ return trim(str_replace(["\r","\n"], ' ', strip_tags((string)$v))); }
$nombre = limpio($_POST['nombre'] ?? ''); $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
if (!$nombre || !$email) { http_response_code(422); exit('<meta charset="utf-8"><p style="font-family:sans-serif">Falta tu nombre o un email válido. <a href="contacto.html">Volvé al formulario</a> y completalo.</p>'); }
$cuerpo = "Nombre: $nombre\nEmpresa: ".limpio($_POST['empresa'] ?? '')."\nEmail: $email\nTeléfono: ".limpio($_POST['tel'] ?? '')."\nTipo: ".limpio($_POST['tipo'] ?? '')
  ."\nMaterial: ".limpio($_POST['material'] ?? '')."\nCantidad: ".limpio($_POST['cantidad'] ?? '')
  ."\n\nDetalle:\n".strip_tags((string)($_POST['detalle'] ?? ''));
$asunto = 'Cotización web - '.$nombre;
$b = md5(uniqid('', true)); $h = "From: web@aceroselizaldesa.com.ar\r\nReply-To: $email\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"$b\"\r\n";
$msg = "--$b\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n$cuerpo\r\n";
if (!empty($_FILES['plano']['tmp_name']) && $_FILES['plano']['error'] === 0 && $_FILES['plano']['size'] <= 8*1024*1024) {
  $ext = strtolower(pathinfo($_FILES['plano']['name'], PATHINFO_EXTENSION));
  if (in_array($ext, ['pdf','dwg','dxf','jpg','jpeg','png'], true)) {
    $n = 'plano.'.$ext; $d = chunk_split(base64_encode(file_get_contents($_FILES['plano']['tmp_name'])));
    $msg .= "--$b\r\nContent-Type: application/octet-stream; name=\"$n\"\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"$n\"\r\n\r\n$d\r\n";
  }
}
$msg .= "--$b--";
$ok = mail($para, '=?UTF-8?B?'.base64_encode($asunto).'?=', $msg, $h);
header('Content-Type: text/html; charset=utf-8');
echo '<meta charset="utf-8">';
echo $ok ? '<p style="font-family:sans-serif">Gracias, recibimos tu consulta y te respondemos en horario comercial. <a href="index.html">Volver al inicio</a></p>' : '<p style="font-family:sans-serif">No pudimos enviar el mensaje. Probá de nuevo o escribinos por WhatsApp.</p>';
