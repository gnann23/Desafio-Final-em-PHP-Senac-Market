<?php

function calcularDescontoTotal(float $valorBruto, string $tipoCliente, string $formaPagamento, int $idade): float {
    $percentual = 0.0;

    if ($valorBruto >= 1000.0) {
        $percentual += 15.0;
    } elseif ($valorBruto >= 500.0) {
        $percentual += 10.0;
    } elseif ($valorBruto >= 200.0) {
        $percentual += 5.0;
    }

    // Regra 2: Cliente Premium
    if (strtolower(trim($tipoCliente)) === 'premium') {
        $percentual += 3.0;
    }

    // Regra 3: Pagamento PIX (comparação estrita)
    if (strtolower(trim($formaPagamento)) === 'pix') {
        $percentual += 2.0;
    }

    // Regra 4: Idade igual ou superior a 60 anos
    if ($idade >= 60) {
        $percentual += 2.0;
    }

    return $percentual;
}

/**
 * Percorre os produtos e extrai métricas gerenciais
 */
function obterEstatisticasProdutos(array $produtos): array {
    $unidadesTotais = 0;
    $produtoMaisCaro = $produtos[0] ?? null;
    $produtoMaisBarato = $produtos[0] ?? null;

    foreach ($produtos as $p) {
        $unidadesTotais += $p['quantidade'];

        if ($produtoMaisCaro === null || $p['preco'] > $produtoMaisCaro['preco']) {
            $produtoMaisCaro = $p;
        }

        if ($produtoMaisBarato === null || $p['preco'] < $produtoMaisBarato['preco']) {
            $produtoMaisBarato = $p;
        }
    }

    return [
        'unidades_totais' => $unidadesTotais,
        'mais_caro' => $produtoMaisCaro,
        'mais_barato' => $produtoMaisBarato
    ];
}

/**
 * Classifica o porte da venda pelo valor final
 */
function classificarVenda(float $valorFinal): string {
    if ($valorFinal < 300.0) {
        return "VENDA PEQUENA";
    } elseif ($valorFinal < 1000.0) {
        return "VENDA MÉDIA";
    } else {
        return "VENDA DE ALTO VALOR";
    }
}

/**
 * Exibe a simulação de parcelamento no cartão em até 6x
 */
function exibirSimulacaoCartao(float $valorFinal): void {
    echo PHP_EOL . "--- SIMULAÇÃO DE PARCELAMENTO (CARTÃO) ---" . PHP_EOL;
    for ($i = 1; $i <= 6; $i++) {
        $valorParcela = $valorFinal / $i;
        echo "{$i}x de " . formatarMoeda($valorParcela) . " (sem juros)" . PHP_EOL;
    }
}

// ============================================================================
// EXECUÇÃO DA APLICAÇÃO (TERMINAL)
// ============================================================================

echo "==================================================" . PHP_EOL;
echo "                 SENAC MARKET                     " . PHP_EOL;
echo "             Sistema de Vendas v1.0               " . PHP_EOL;
echo "==================================================" . PHP_EOL . PHP_EOL;

// 1. Identificação Automática da Venda
$idVenda = rand(10000, 99999);
$dataVenda = date('d/m/Y H:i');

echo "Venda Nº: #{\(idVenda} | Data: {\)dataVenda}" . PHP_EOL . PHP_EOL;

// 2. Dados do Cliente
echo "--- DADOS DO CLIENTE ---" . PHP_EOL;
$nomeCliente = readline("Nome do cliente: ");
$tipoCliente = readline("Tipo do cliente (padrao / premium): ");
$idadeCliente = (int) readline("Idade do cliente: ");

// 3. Cadastro de Produtos
echo PHP_EOL . "--- CADASTRO DE PRODUTOS ---" . PHP_EOL;
echo "(Digite 'ENCERRAR' no nome do produto para finalizar o cadastro)" . PHP_EOL . PHP_EOL;

$produtos = [];
$valorBruto = 0.0;

while (true) {
    $nomeProduto = readline("Nome do produto: ");

    if (strtoupper(trim($nomeProduto)) === 'ENCERRAR') {
        break;
    }

    $categoria = readline("Categoria: ");
    $preco = (float) readline("Preço unitário (R$): ");
    $quantidade = (int) readline("Quantidade: ");

    // Validação de Integridade
    if ($preco <= 0 || $quantidade <= 0) {
        echo "[AVISO] Produto ignorado: Preço e quantidade devem ser maiores que zero!" . PHP_EOL . PHP_EOL;
        continue;
    }

    $subtotal = $preco * $quantidade;
    $valorBruto += $subtotal;

    // Armazenamento estruturado no Array Multidimensional
    $produtos[] = [
        'nome' => $nomeProduto,
        'categoria' => $categoria,
        'preco' => $preco,
        'quantidade' => $quantidade,
        'subtotal' => $subtotal
    ];

    echo "[SUCESSO] Produto adicionado!" . PHP_EOL . PHP_EOL;
}

// Caso nenhum produto seja cadastrado
if (empty($produtos)) {
    echo PHP_EOL . "Nenhum produto válido foi cadastrado. A venda foi cancelada." . PHP_EOL;
    exit;
}

// 4. Forma de Pagamento
echo PHP_EOL . "--- FORMA DE PAGAMENTO ---" . PHP_EOL;
$formaPagamento = strtolower(trim(readline("Informe a forma de pagamento (pix / cartao / dinheiro): ")));

while ($formaPagamento !== 'pix' && $formaPagamento !== 'cartao' && $formaPagamento !== 'dinheiro') {
    echo "[ERRO] Forma de pagamento inválida!" . PHP_EOL;
    $formaPagamento = strtolower(trim(readline("Informe a forma de pagamento (pix / cartao / dinheiro): ")));
}

// 5. Cálculos Financeiros
$pctDesconto = calcularDescontoTotal($valorBruto, $tipoCliente, $formaPagamento, $idadeCliente);
$valorDesconto = $valorBruto * ($pctDesconto / 100);
$valorFinal = $valorBruto - $valorDesconto;

// 6. GERAÇÃO DO COMPROVANTE
echo PHP_EOL;
echo "======================================================================" . PHP_EOL;
echo "                        COMPROVANTE DE VENDA                          " . PHP_EOL;
echo "======================================================================" . PHP_EOL;
echo "SENAC MARKET | Venda Nº: #{$idVenda} | Data: {$dataVenda}" . PHP_EOL;
echo "Cliente: {$nomeCliente} | Tipo: " . ucfirst($tipoCliente) . " | Idade: {$idadeCliente} anos" . PHP_EOL;
echo "----------------------------------------------------------------------" . PHP_EOL;
echo sprintf("%-20s %-12s %-12s %-6s %-12s", "PRODUTO", "CATEGORIA", "PREÇO UN.", "QTD", "SUBTOTAL") . PHP_EOL;
echo "----------------------------------------------------------------------" . PHP_EOL;

foreach ($produtos as $p) {
    echo sprintf(
        "%-20s %-12s %-12s %-6d %-12s",
        substr($p['nome'], 0, 18),
        substr($p['categoria'], 0, 10),
        formatarMoeda($p['preco']),
        $p['quantidade'],
        formatarMoeda($p['subtotal'])
    ) . PHP_EOL;
}

echo "----------------------------------------------------------------------" . PHP_EOL;
echo "Forma de Pagamento: " . strtoupper($formaPagamento) . PHP_EOL;

if ($formaPagamento === 'cartao') {
    exibirSimulacaoCartao($valorFinal);
}

// 7. RELATÓRIO GERENCIAL
$estatisticas = obterEstatisticasProdutos($produtos);
$classificacao = classificarVenda($valorFinal);

echo PHP_EOL;
echo "======================================================================" . PHP_EOL;
echo "                         RELATÓRIO GERENCIAL                          " . PHP_EOL;
echo "======================================================================" . PHP_EOL;
echo "Qtd. de Produtos Diferentes : " . count($produtos) . PHP_EOL;
echo "Qtd. Total de Unidades      : " . $estatisticas['unidades_totais'] . PHP_EOL;
echo "Produto Mais Caro           : " . $estatisticas['mais_caro']['nome'] . " (" . formatarMoeda($estatisticas['mais_caro']['preco']) . ")" . PHP_EOL;
echo "Produto Mais Barato         : " . $estatisticas['mais_barato']['nome'] . " (" . formatarMoeda($estatisticas['mais_barato']['preco']) . ")" . PHP_EOL;
echo "----------------------------------------------------------------------" . PHP_EOL;
echo "Valor Bruto                 : " . formatarMoeda($valorBruto) . PHP_EOL;
echo "Percentual de Desconto      : " . number_format($pctDesconto, 1, ',', '.') . "%" . PHP_EOL;
echo "Valor Concedido em Desconto : " . formatarMoeda($valorDesconto) . PHP_EOL;
echo "VALOR FINAL RECEBIDO        : " . formatarMoeda($valorFinal) . PHP_EOL;
echo "----------------------------------------------------------------------" . PHP_EOL;
echo "CLASSIFICAÇÃO DA VENDA      : " . $classificacao . PHP_EOL;
echo "======================================================================" . PHP_EOL;