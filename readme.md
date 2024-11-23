# Valorease: Web Scraper

O presente projeto foi desenvolvido para o curso de Análise e Desenvolvimento de Sistemas da Unimar (matéria Fábrica de Projetos II). Ele se propõe na implementação de um web scraper capaz de obter o preço atual de um/vários produto(s) em lojas virtuais (Mercado Livre).

Para a criação, foi escolhido a linguagem PHP na versão 8.4.

# Como rodar localmente?

Para rodar, é recomendado que você tente utilizando Docker, para isso, siga os seguintes comandos:

Clone o projeto em sua máquina:

```bash
λ git clone https://github.com/valorease/web-scraper.git
```

Com o projeto clonado, você deve rodar ele:

```bash
λ docker compose up -d
```

Ele estará rodando de plano de fundo, agora, você deve entrar dentro do container:

```bash
λ docker exec -it web-scraper-php sh
```

Feito isso, você tem a opção de rodar como [API](#api):

```bash
λ php -S localhost:5050 /public/api.php
```

Ou como [dependente](#dependente):

```bash
λ php /public/cli.php
```

# Seu funcionamento

O projeto pode funcionar de duas formas diferentes:

- Uma API que recebe requisições com pedidos de pesquisa de produtos, devolvendo o resultado após;
- Ou como um microsserviço que depende de uma API externa, enviando requisições pedindo produtos para serem pesquisados e, após, enviando novas requisições com os resultados (rodando assim em um loop eterno).

Você deve usar da maneira que preferir, as duas formas são documentadas:

## API

Como API, o projeto possui uma rota:

```
[POST] http://localhost:5050/product
[Content-Type] application/json
[Body] {
  "slug": "iphone-15",
  "target": "ML"
}
```

É esperado que ela retorne algo como:

```
[Content-Type] application/json
[Body] {
  "target": "ML",
  "prices": [
    {
      "price": 3500,
      "url": "..."
    },
    [...]
  ]
}
```

## Dependente
