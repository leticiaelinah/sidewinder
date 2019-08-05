# sidewinder
Carro do jogo Top Gear inspirado na Ferrari 288 GTO de 1985, se destaca pelo menor consumo de combustível e pela forte aderência dos pneus em curvas. É a melhor opção de escolha do jogo. Atinge 60 mph em 3,5 segundos.

Este repositório calcula e mostra a posição chegada de cada piloto, assim como a quantidade de voltas que cada piloto completou, a melhor volta e a velocidade média de cada um, a melhor volta da corrida e o tempo total de prova.

# How to run

- Clonar o repositório;
- Alterar o arquivo config.php para os dados do seu banco;
- Na pasta "uteis" do projeto, utilizar o arquivo chamado createDB.sql para preparar seu banco de dados;
- No arquivo httpd.conf, alterar o "DocumentRoot" para a recém-clonada pasta sidewinder;
- Iniciar o apache;
- Abrir no navegador o arquivo index.php na raíz do projeto.