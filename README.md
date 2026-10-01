A SENAC Market precisa de uma aplicação em PHP executada pelo terminal para registrar uma venda completa.
Ao iniciar o programa, a venda deve receber uma identificação automática e registrar a data atual. Em seguida, o sistema deverá receber os dados básicos do cliente e permitir o cadastro de quantos produtos forem necessários.
Cada produto possui nome, categoria, preço unitário e quantidade. Os produtos válidos deverão permanecer disponíveis no sistema até o término da venda, pois ao final será necessário percorrê-los novamente para produzir o comprovante e o relatório gerencial.
Dados incoerentes não deverão contaminar os cálculos. Um produto com preço ou quantidade inválidos deverá ser informado ao usuário e ignorado, permitindo que o cadastro continue normalmente. Caso seja informado ENCERRAR como nome de um produto, a etapa de cadastro deverá terminar imediatamente.
Durante o processamento da venda, o sistema deverá ser capaz de descobrir o subtotal de cada item e, considerando todos os produtos válidos, identificar:
quantidade de produtos diferentes;
quantidade total de unidades;
valor bruto da compra;
produto de maior preço unitário;
produto de menor preço unitário.
A SENAC Market possui uma política de descontos.
Compras abaixo de R$ 200,00 não recebem desconto pelo valor da compra. A partir de R$ 200,00 o desconto é de 5%; a partir de R$ 500,00 passa para 10%; e compras a partir de R$ 1.000,00 recebem 15%.
Outras características da venda podem aumentar esse percentual. Clientes do tipo premium recebem mais 3%, pagamentos realizados exatamente por pix recebem mais 2% e clientes com 60 anos ou mais recebem mais 2%.
Essas regras são cumulativas.
Depois de descobrir o percentual final, o programa deverá calcular quanto foi concedido em desconto e qual será o valor efetivamente pago pelo cliente.
A forma de pagamento poderá ser:
pix
cartao
dinheiro
Quando o pagamento for realizado com cartão, o sistema deverá permitir no máximo seis parcelas e apresentar ao cliente uma simulação mostrando quanto a compra custaria de 1 até 6 vezes.
Ao finalizar, o sistema deverá gerar um comprovante organizado contendo os dados da venda, do cliente e de todos os produtos cadastrados. Para cada produto devem aparecer, no mínimo, nome, categoria, preço, quantidade e subtotal.
Depois do comprovante, apresente um pequeno relatório gerencial contendo:
Quantidade de produtos diferentes
Quantidade total de unidades vendidas
Produto mais caro
Produto mais barato
Valor bruto
Percentual de desconto
Valor concedido em desconto
Valor final recebido
