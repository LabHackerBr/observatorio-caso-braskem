# Observatório Caso Braskem: Portal Informativo

Este software é um portal informativo e uma plataforma de dados construída em WordPress, destinada a documentar e apresentar informações sobre o caso Braskem.

---

## Contextualização

Este projeto serve como um observatório digital para o caso Braskem, centralizando e disponibilizando informações de interesse público. A plataforma é destinada a pesquisadores, jornalistas, estudantes e à sociedade civil, oferecendo acesso a uma linha do tempo interativa, mapas, uma biblioteca de documentos e um arquivo de histórias relacionadas ao caso.

---

## Estrutura do Projeto

O projeto utiliza uma arquitetura baseada em containers (Docker) para isolamento do ambiente de desenvolvimento:

* **/themes/observatorio-caso-braskem/**: Contém o tema customizado (Templates, SCSS e JS).
* **/plugins/**: Diretório para plugins customizados e submódulos (ex: `hacklab-blocks`).
* **/dev-scripts/**: Scripts de automação para banco de dados e comandos WP-CLI.
* **/compose/**: Configurações de infraestrutura local e personalizações do PHP (`extra.ini`).

> **Nota sobre versionamento:** O conteúdo de `wp-content` está no `.gitignore`. Para adicionar plugins ou temas ao repositório, coloque-os diretamente nas pastas `plugins/` ou `themes/` da raiz.

---

## Instalação e Execução

### Requisitos
* Git, Docker e Docker Compose, Node.js e npm.

### Guia de Instalação

1.  **Clone o Repositório:**
    ```bash
    git clone --recursive <URL_DO_REPOSITORIO_GIT>
    ```

2.  **Importação do Banco de Dados (Opcional):**
    Copie seu dump `.sql` ou `.sql.gz` para `compose/local/mariadb/data/`.
    *Se os containers já existirem, use `docker-compose down -v` antes de subir novamente.*

3.  **Instale as Dependências do Tema:**
    ```bash
    cd themes/observatorio-caso-braskem/
    npm install
    npm run watch # Para compilar assets em tempo real
    ```

4.  **Inicie o Ambiente:**
    Na raiz do projeto: `docker-compose up -d`. Acesse em [http://localhost](http://localhost).

---

## Configurações e Debug

### Customização do Ambiente
* **Variáveis WordPress:** Editáveis no `docker-compose.yml` (ex: `WORDPRESS_DEBUG: 1`).
* **Configurações PHP:** Edite `compose/local/wordpress/php/extra.ini` e reinicie os containers.

### Debug Interativo (PSY)
1. Certifique-se de que o plugin `hacklab-dev-utils` está ativo.
2. Execute o script: `./dev-scripts/dev.sh`.
3. No código PHP, insira: `eval(\psy\sh());`.
4. Para facilitar, adicione o snippet abaixo no seu VS Code (User Snippets > PHP):
```json
"psy": { "prefix": "psy", "body": ["eval(\\psy\\sh());"] }
```

---

## Guia de Uso e Desenvolvimento

### Como o Software Funciona
A plataforma utiliza tipos de post customizados (CPTs) para organizar os dados:
* **Biblioteca**: Documentos oficiais e pesquisas.
* **Storymap**: Narrativas geográficas sobre os impactos.
* **Map & Linha do Tempo**: Visualizações interativas de eventos e locais.

### Scripts Úteis
A pasta `dev-scripts/` centraliza comandos frequentes para evitar a complexidade do Docker:
* `./dev-scripts/wp <comando>`: Executa comandos WP-CLI dentro do container.
* `./dev-scripts/dump > backup.sql`: Gera um dump do banco de dados atual.
* `./dev-scripts/mysql`: Acessa o terminal interativo do MySQL.

### Traduções
Se as traduções de arquivos `.js` via `wp i18n` não carregarem no navegador, verifique se o arquivo JSON gerado segue o padrão de nomenclatura: 
`{domain}-{locale}-{script-handle}.json`

---

## Fluxo de CI/CD e Deploy

O deploy é automatizado via **GitHub Actions** em conjunto com o plugin **GitUpdater**.

### Requisitos de Configuração
Para o funcionamento correto, o arquivo `.github/workflows/main.yml` deve conter as seguintes variáveis:
* **CURL_URL**: URL do endpoint de atualização (webhook) do ambiente.
* **DEPLOY**: Deve ser configurado com um valor diferente de `0` para permitir o release automático.

### Realizando um Release
Para atualizar o ambiente de staging/produção, siga rigorosamente este fluxo:

1. Atualize a versão do tema no arquivo `style.css`.
2. Certifique-se de que a branch `develop` está mesclada em `main`.
3. Crie e envie uma **Tag** no Git com o **mesmo número** da versão definida no `style.css`:

```bash
git tag -a 1.0.2 -m "v1.0.2"
git push origin refs/tags/1.0.2
```

> **Atenção:** Se a versão da Tag e a versão no style.css forem diferentes, o plugin GitUpdater não reconhecerá a atualização e o deploy falhará.
