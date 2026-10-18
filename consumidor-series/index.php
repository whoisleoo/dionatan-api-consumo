<?php
$api = "http://127.0.0.1:8000/api/series";

function chamarApi($metodo, $url, $dados = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json']);
    if ($dados != null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
    }
    $resposta = curl_exec($ch);
    curl_close($ch);
    return json_decode($resposta, true);
}

$msg = '';

if (isset($_POST['acao'])) {
    if ($_POST['acao'] == 'salvar') {
        $serie = [
            'nome' => $_POST['nome'],
            'genero' => $_POST['genero'],
            'temporadas' => $_POST['temporadas'],
            'nota' => $_POST['nota'] != '' ? $_POST['nota'] : null
        ];

        if ($_POST['id'] != '') {
            $ret = chamarApi('PUT', $api . '/' . $_POST['id'], $serie);
        } else {
            $ret = chamarApi('POST', $api, $serie);
        }

        if (isset($ret['errors'])) {
            $msg = 'Erro: ' . $ret['message'];
        } else {
            $msg = 'Serie salva!';
        }
    }

    if ($_POST['acao'] == 'excluir') {
        $ret = chamarApi('DELETE', $api . '/' . $_POST['id']);
        $msg = $ret['mensagem'];
    }
}

$editando = null;
if (isset($_GET['editar'])) {
    $ret = chamarApi('GET', $api . '/' . (int)$_GET['editar']);
    if (isset($ret['data'])) $editando = $ret['data'];
}

$lista = chamarApi('GET', $api);
if ($lista === null) {
    $msg = 'Nao foi possivel conectar na API, verifique se ela esta rodando (php artisan serve)';
    $series = [];
} else {
    $series = $lista['data'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>SERIES</title>
<style>
body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 0 15px; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
th { background: #333; color: white; }
form.cadastro input { padding: 6px; margin: 4px 0; }
.msg { background: #e3f2d9; padding: 10px; border: 1px solid #9c6; }
button { cursor: pointer; }
</style>
</head>
<body>

<h1>SERIES</h1>
<p>projetinho que consome api de serie (api-series)</p>

<?php if ($msg != ''): ?>
    <div class="msg"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<h3><?= $editando ? 'Editar serie' : 'Cadastrar serie' ?></h3>
<form method="post" class="cadastro">
    <input type="hidden" name="acao" value="salvar">
    <input type="hidden" name="id" value="<?= $editando ? $editando['id'] : '' ?>">
    Nome: <input type="text" name="nome" value="<?= $editando ? htmlspecialchars($editando['nome']) : '' ?>" required><br>
    Genero: <input type="text" name="genero" value="<?= $editando ? htmlspecialchars($editando['genero']) : '' ?>" required><br>
    Temporadas: <input type="number" name="temporadas" min="1" value="<?= $editando ? $editando['temporadas'] : '' ?>" required><br>
    Nota: <input type="number" name="nota" step="0.1" min="0" max="10" value="<?= $editando ? $editando['nota'] : '' ?>"><br>
    <button type="submit">Salvar</button>
    <?php if ($editando): ?>
        <a href="index.php">Cancelar</a>
    <?php endif; ?>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Genero</th>
        <th>Temporadas</th>
        <th>Nota</th>
        <th>Cadastro</th>
        <th></th>
    </tr>
    <?php foreach ($series as $s): ?>
    <tr>
        <td><?= $s['id'] ?></td>
        <td><?= htmlspecialchars($s['nome']) ?></td>
        <td><?= htmlspecialchars($s['genero']) ?></td>
        <td><?= $s['temporadas'] ?></td>
        <td><?= $s['nota'] ?? '-' ?></td>
        <td><?= $s['cadastrado_em'] ?></td>
        <td>
            <a href="?editar=<?= $s['id'] ?>">Editar</a>
            <form method="post" style="display:inline" onsubmit="return confirm('Excluir essa serie?')">
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <button type="submit">Excluir</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (count($series) == 0): ?>
    <tr><td colspan="7">Nenhuma serie cadastrada</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
