<?php
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$nome = isset($_GET['nome']) ? trim($_GET['nome']) : '';

if ($pagina < 1) $pagina = 1;

$url = "https://rickandmortyapi.com/api/character/?page=" . $pagina;
if ($nome != '') {
    $url .= "&name=" . urlencode($nome);
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$resposta = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$dados = json_decode($resposta, true);

$personagens = [];
$totalPaginas = 1;
if ($status == 200) {
    $personagens = $dados['results'];
    $totalPaginas = $dados['info']['pages'];
}

function traduzStatus($s) {
    if ($s == 'Alive') return 'Vivo';
    if ($s == 'Dead') return 'Morto';
    return 'Desconhecido';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Personagens - Rick and Morty</title>
<style>
body { font-family: Arial, sans-serif; background: #1e1e2f; color: #fff; margin: 0; padding: 20px; }
h1 { text-align: center; color: #97ce4c; }
form { text-align: center; margin-bottom: 20px; }
input[type=text] { padding: 8px; width: 250px; border-radius: 4px; border: none; }
button { padding: 8px 14px; background: #97ce4c; border: none; border-radius: 4px; cursor: pointer; }
.lista { display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; }
.card { background: #2b2b40; width: 220px; border-radius: 8px; overflow: hidden; }
.card img { width: 100%; }
.card .info { padding: 10px; }
.card h3 { margin: 0 0 8px 0; font-size: 17px; }
.card p { margin: 4px 0; font-size: 14px; color: #ccc; }
.vivo { color: #55cc44 !important; }
.morto { color: #d63d2e !important; }
.paginacao { text-align: center; margin: 25px 0; }
.paginacao a { color: #97ce4c; margin: 0 10px; }
</style>
</head>
<body>

<h1>Rick and Morty - Personagens</h1>

<form method="get">
    <input type="text" name="nome" placeholder="Buscar pelo nome..." value="<?= htmlspecialchars($nome) ?>">
    <button type="submit">Buscar</button>
</form>

<?php if (count($personagens) == 0): ?>
    <p style="text-align:center">Nenhum personagem encontrado :(</p>
<?php endif; ?>

<div class="lista">
<?php foreach ($personagens as $p): ?>
    <div class="card">
        <img src="<?= $p['image'] ?>" alt="<?= htmlspecialchars($p['name']) ?>">
        <div class="info">
            <h3><?= htmlspecialchars($p['name']) ?></h3>
            <?php
            $classe = '';
            if ($p['status'] == 'Alive') $classe = 'vivo';
            if ($p['status'] == 'Dead') $classe = 'morto';
            ?>
            <p class="<?= $classe ?>">Status: <?= traduzStatus($p['status']) ?></p>
            <p>Especie: <?= $p['species'] ?></p>
            <p>Origem: <?= $p['origin']['name'] ?></p>
            <p>Episodios: <?= count($p['episode']) ?></p>
        </div>
    </div>
<?php endforeach; ?>
</div>

<div class="paginacao">
    <?php if ($pagina > 1): ?>
        <a href="?pagina=<?= $pagina - 1 ?>&nome=<?= urlencode($nome) ?>">&laquo; Anterior</a>
    <?php endif; ?>
    Pagina <?= $pagina ?> de <?= $totalPaginas ?>
    <?php if ($pagina < $totalPaginas): ?>
      <a href="?pagina=<?= $pagina + 1 ?>&nome=<?= urlencode($nome) ?>">Proxima &raquo;</a>
    <?php endif; ?>
</div>

</body>
</html>
