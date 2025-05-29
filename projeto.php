<?php

$usuarios = [
    ['login' => 'admin', 'senha' => 'admin']
];

$usuarioLogado = null;
$totalVendas = 0;
$registros = [];

$produtos = [
    ['id' => 1, 'nome' => 'Shampoo', 'preco' => 25.50],
    ['id' => 2, 'nome' => 'Condicionador', 'preco' => 22.00],
    ['id' => 3, 'nome' => 'Máscara Capilar', 'preco' => 38.90],
    ['id' => 4, 'nome' => 'Protetor Solar', 'preco' => 65.00],
    ['id' => 5, 'nome' => 'Creme Hidratante Corporal', 'preco' => 45.00],
    ['id' => 6, 'nome' => 'Batom Matte', 'preco' => 29.90],
    ['id' => 7, 'nome' => 'Delineador Líquido', 'preco' => 35.00],
    ['id' => 8, 'nome' => 'Base Líquida', 'preco' => 75.00],
    ['id' => 9, 'nome' => 'Perfume Floral 50ml', 'preco' => 120.00],
    ['id' => 10, 'nome' => 'Sabonete Facial', 'preco' => 30.50],
];

function limparTela() {
    system('clear');
}

function adicionarAoRegistro($mensagem) {
    global $registros, $usuarioLogado;
    $timestamp = date('d/m/Y H:i:s');
    $identificadorUsuario = $usuarioLogado ? $usuarioLogado['login'] : 'Sistema';
    $registros[] = "{$identificadorUsuario} {$mensagem} às {$timestamp}.";
}

function pressioneParaContinuar() {
    echo "\nPressione qualquer tecla para continuar...";
    readline();
}

function fazerLogin(&$usuarios, &$usuarioLogado) {
    limparTela();
    echo "--- LOGIN ---\n";
    echo "Login: ";
    $loginDigitado = trim(readline());
    echo "Senha: ";
    $senhaDigitada = trim(readline());

    foreach ($usuarios as $usuario) {
        if ($usuario['login'] === $loginDigitado && $usuario['senha'] === $senhaDigitada) {
            $usuarioLogado = $usuario;
            adicionarAoRegistro("realizou login com sucesso");
            echo "\nLogin realizado com sucesso! Bem-vindo(a), {$usuarioLogado['login']}!\n";
            pressioneParaContinuar();
            return;
        }
    }
    echo "\nLogin ou senha inválidos. Tente novamente.\n";
    adicionarAoRegistro("tentou realizar login com credenciais inválidas");
    pressioneParaContinuar();
}

function fazerLogout(&$usuarioLogado) {
    limparTela();
    if ($usuarioLogado) {
        adicionarAoRegistro("realizou logout");
        echo "Você foi deslogado com sucesso, {$usuarioLogado['login']}.\n";
        $usuarioLogado = null;
    } else {
        echo "Nenhum usuário logado.\n";
    }
    pressioneParaContinuar();
}

function cadastrarNovoUsuario(&$usuarios) {
    global $usuarioLogado;
    limparTela();
    echo "--- CADASTRO DE NOVO USUÁRIO ---\n";
    echo "Novo Login: ";
    $novoLogin = trim(readline());

    foreach ($usuarios as $usuario) {
        if ($usuario['login'] === $novoLogin) {
            echo "Erro: Login '{$novoLogin}' já existe. Escolha outro.\n";
            adicionarAoRegistro("tentou cadastrar novo usuário com login existente ({$novoLogin})");
            pressioneParaContinuar();
            return;
        }
    }

    echo "Nova Senha: ";
    $novaSenha = trim(readline());

    $usuarios[] = ['login' => $novoLogin, 'senha' => $novaSenha];
    adicionarAoRegistro("cadastrou um novo usuário: {$novoLogin}");
    echo "\nUsuário '{$novoLogin}' cadastrado com sucesso!\n";
    pressioneParaContinuar();
}

function realizarVenda(&$totalVendas) {
    global $usuarioLogado, $produtos;
    limparTela();
    echo "--- REALIZAR VENDA ---\n";

    echo "\n--- Produtos Disponíveis ---\n";
    foreach ($produtos as $produto) {
        echo "{$produto['id']}. {$produto['nome']} - R$ " . number_format($produto['preco'], 2, ',', '.') . "\n";
    }
    echo "---------------------------\n";

    $produtoSelecionado = null;
    $nomeItem = '';
    $valorVenda = 0;

    while (true) {
        echo "Digite o ID do produto ou '0' para informar o nome/valor manualmente: ";
        $opcaoEntrada = trim(readline());

        if ($opcaoEntrada === '0') {
            echo "Nome do item vendido: ";
            $nomeItem = trim(readline());
            while (true) {
                echo "Valor da venda: R$ ";
                $valorEntrada = trim(readline());
                if (is_numeric($valorEntrada) && $valorEntrada > 0) {
                    $valorVenda = (float)$valorEntrada;
                    break;
                } else {
                    echo "Valor inválido. Por favor, digite um número positivo.\n";
                }
            }
            break;
        } else {
            $encontrado = false;
            foreach ($produtos as $produto) {
                if ((int)$opcaoEntrada === $produto['id']) {
                    $produtoSelecionado = $produto;
                    $nomeItem = $produtoSelecionado['nome'];
                    $valorVenda = $produtoSelecionado['preco'];
                    $encontrado = true;
                    break;
                }
            }
            if ($encontrado) {
                echo "Produto selecionado: {$nomeItem} (R$ " . number_format($valorVenda, 2, ',', '.') . ")\n";
                pressioneParaContinuar();
                break;
            } else {
                echo "ID de produto inválido. Tente novamente.\n";
            }
        }
    }

    $totalVendas += $valorVenda;
    adicionarAoRegistro("realizou uma venda do item '{$nomeItem}' no valor de R$ " . number_format($valorVenda, 2, ',', '.'));
    echo "\nVenda de '{$nomeItem}' (R$ " . number_format($valorVenda, 2, ',', '.') . ") registrada com sucesso!\n";
    pressioneParaContinuar();
}

function verRegistro() {
    global $registros;
    limparTela();
    echo "--- HISTÓRICO DO SISTEMA ---\n";
    if (empty($registros)) {
        echo "Nenhuma atividade registrada ainda.\n";
    } else {
        foreach ($registros as $entrada) {
            echo $entrada . "\n";
        }
    }
    pressioneParaContinuar();
}

while (true) {
    limparTela();
    echo "--- SISTEMA DE GERENCIAMENTO DE CAIXA ---\n";

    if ($usuarioLogado) {
        echo "Usuário logado: {$usuarioLogado['login']}\n";
        echo "Total de vendas: R$ " . number_format($totalVendas, 2, ',', '.') . "\n";
        echo "\nEscolha uma opção:\n";
        echo "1. Realizar Venda\n";
        echo "2. Cadastrar Novo Usuário\n";
        echo "3. Verificar Histórico (Registro)\n";
        echo "4. Deslogar\n";
        echo "Opção: ";
        $escolha = trim(readline());

        switch ($escolha) {
            case '1':
                realizarVenda($totalVendas);
                break;
            case '2':
                cadastrarNovoUsuario($usuarios);
                break;
            case '3':
                verRegistro();
                break;
            case '4':
                fazerLogout($usuarioLogado);
                break;
            default:
                echo "Opção inválida. Tente novamente.\n";
                pressioneParaContinuar();
                break;
        }
    } else {
        echo "\nNenhum usuário logado. Por favor, faça o login.\n";
        echo "1. Fazer Login\n";
        echo "Opção: ";
        $escolha = trim(readline());

        switch ($escolha) {
            case '1':
                fazerLogin($usuarios, $usuarioLogado);
                break;
            default:
                echo "Opção inválida. Tente novamente.\n";
                pressioneParaContinuar();
                break;
        }
    }
}