<?php


if(isset($_POST['email']) && !empty($_POST['email']))

$nome = $_POST['name'];
$email = $_POST['email']; // Email que será respondido
$telefone = $_POST['telefone'];
$arquivo = $_FILES['file'];
$assunto = $_POST['assunto'];
$mensagem_form = $_POST['message'];


$to = "ncs@ncsengenharia.com" .",";
$to .= "jota.cunha@ncsengenharia.com" .",";
$to .= "daniel.nasser@ncsengenharia.com" .",";
$to .= "paulo.santos@ncsengenharia.com";
$remetente = "contato@ncsengenharia.com"; // Deve ser um email válido do domínio
$subject = "Email de Contato via site";

$boundary = "XYZ-" . date("dmYis") . "-ZYX";
$headers = "MIME-Version: 1.0 \r\n";
$headers.= "From: $remetente \r\n";
$headers.= "Reply-To: $email \r\n";
$headers.= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";  
$headers.= "$boundary\n"; 


$body = "
        <br>Formulário via site
        <br>--------------------------------------------<br>
        <br><strong>Nome:</strong> $nome
        <br><strong>Email:</strong> $email
        <br><strong>Assunto:</strong> $assunto
        <br><strong>Mensagem:</strong> $mensagem_form
        <br><br>--------------------------------------------
        ";

if(file_exists($arquivo["tmp_name"]) and !empty($arquivo)){

    $fp = fopen($_FILES["file"]["tmp_name"],"rb"); // Abri o arquivo enviado.
    $anexo = fread($fp,filesize($_FILES["file"]["tmp_name"])); // Le o arquivo aberto na linha anterior
    $anexo = base64_encode($anexo); // Codifica os dados com MIME para o e-mail 
    fclose($fp); // Fecha o arquivo aberto anteriormente
    $anexo = chunk_split($anexo); // Divide a variável do arquivo em pequenos pedaços para poder enviar
    $mensagem = "--$boundary\n"; // Nas linhas abaixo possuem os parâmetros de formatação e codificação, juntamente com a inclusão do arquivo anexado no corpo da mensagem
    $mensagem.= "Content-Transfer-Encoding: 8bits\n"; 
    $mensagem.= "Content-Type: text/html; charset=\"utf-8\"\n\n";
    $mensagem.= "$body\n"; 
    $mensagem.= "--$boundary\n"; 
    $mensagem.= "Content-Type: ".$arquivo["type"]."\n";  
    $mensagem.= "Content-Disposition: attachment; filename=\"".$arquivo["name"]."\"\n";  
    $mensagem.= "Content-Transfer-Encoding: base64\n\n";  
    $mensagem.= "$anexo\n";  
    $mensagem.= "--$boundary--\r\n"; 
}
    else // Caso não tenha anexo
    {
    $mensagem = "--$boundary\n"; 
    $mensagem.= "Content-Transfer-Encoding: 8bits\n"; 
    $mensagem.= "Content-Type: text/html; charset=\"utf-8\"\n\n";
    $mensagem.= "$body\n";
}

if(mail($to, $subject, $mensagem, $headers)){
    $mgm = "E-MAIL ENVIADO COM SUCESSO! <br> O link será enviado para o e-mail fornecido no formulário";
    echo "<script>
    alert('Mensagem enviada com sucesso! Em breve estaremos respondendo.');
    window.location.href = 'http://www.ncsengenharia.com/pages/contato.html';
    </script>";
    } else {
        echo "<script>alert(‘Não foi possível enviar o contato, tente mais tarde.’);
        history.go(-1) </script>";
    }
?>