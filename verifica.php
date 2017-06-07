<?php
//Pega os dados enviados pelo formulario
$senha = $_POST['senha'];
$login = $_POST['login'];
$ip = $_SERVER;['REMOTE_ADD'];

$db = "portal"; // nome do banco de dados
$host = "127.0.0.1"; // host (local onde seu banco de dados estï¿½ hospedado, caso seja na sua mï¿½quina use "localhost") DESTAQUE 6
$user = "postgres"; // nome de usuario registrado no banco de dados
$pass = "dba"; // senha do usuario
$port = "5432"; // "porta" para conexao ao banco de dados

$string_connect = "host=$host port=$port dbname=$db user=$user password=$pass"; // Variavel com as informacoes para a conexao ser feita
$connect = pg_pconnect($string_connect); // Comando para conectar-se ao banco

// daqui pra frente tudo se faz a partir dos comandos sql dentro do banco feito pela aplicacao.

$sql = "update nat_client.liberacao set ip = null where ip = '$ip'"; // esta linha deixa o campo ip em branco onde antes existia o
$query = pg_query( $connect,$sql ); //ip do cliente, agora o valor é null, entenderás

$updateip = "update nat_client.liberacao set ip = '$ip' where login = '$login'"; //para atribuir no banco , login ao ip
$queryip = pg_query( $connect,$updateip );

$sql2 = "select * from nat_client.liberacao where login = '$login'";
$query2 = pg_query( $connect,$sql2 );
while ($linha = pg_fetch_array($query2))

$senhabd = $linha['senha'];

if ($senha == $senhabd) {//compara de forma simples, sem criptografia se senha informada e senha existente
// no banco são iguais, no meu caso esse script se encaixou bem
//pq desenvolvi para um hotel e é claro está bem maior e mais complexo que isso, 
//além disso assim não é um login então cada login pode ter sua senha,
// mas se for para um restaurante por exemplo onde todos usarão a mesma senha e login, 
//inves usar UPDATE no sql, use INSERT INTO para inserir os comandos de liberacao na tabela um a um a cada ip liberado,
// não é dificil, leiam um pouco. aqui eles postam muita coisa sobre isso. solicitem que deixo mais algo em seguida.

$sql3 = "UPDATE nat_client.liberacao SET (postrouting, ip_forward, ip_accept) = ('iptables -t nat -A POSTROUTING -s $ip -j MASQUERADE', 'iptables -A FORWARD -s $ip -j ACCEPT', 'iptables -t nat -I PREROUTING -s $ip -j ACCEPT') WHERE ip = '$ip'";//por isso que valeu a pena limpar e escrever o ip no lugar certo.
$query3 = pg_query($connect,$sql3);
echo "<center><h3>Bem vindo ao Meu Estabelecimento</h3></center>";
echo "<center><h3>Welcome to Meu Estabelecimento</h3></center>";
echo "<center><h5>Reinicie seu navegador para validar o seu acesso.</h5></center>"; 
echo "<center><h5>Restart your browser to validate the access.</h5></center>";
}
else
{
echo "<center><h3><font color=red>Login X Senha não conferem, digite novamente!</font></h3></center>";
echo "<center><h3><font color=red>Login X Password do not match, retype!</font></h3></center>";
}
pg_close($connect);
?>