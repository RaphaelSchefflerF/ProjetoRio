# Projeto Rio

Este é um guia para configurar e rodar o Projeto Rio. Para começar, será necessário fazer o download do WampServer e do arquivo ZIP do projeto. Você pode seguir o passo a passo abaixo ou acessar o documento detalhado disponível neste link: [Guia Passo a Passo - Google Docs](https://docs.google.com/document/d/1zUER1A9mwQV3TjhQBsvt8Z4XbM4HS7xhzZi5D2HoHhI/edit?usp=sharing).

## 1. Instalando o WampServer

1.1. Acesse o link: [WampServer no SourceForge](https://sourceforge.net/projects/wampserver/).

1.2. Clique em **Download** e aguarde alguns segundos. Caso ocorra algum erro, clique em **Problems Downloading**.

1.3. Clique em **Direct Link** para iniciar o download.

1.4. Salve o arquivo e aguarde a conclusão do download.

1.5. Execute o instalador e siga todos os passos até finalizar a instalação.

1.6. Durante a instalação, será solicitado que você selecione um navegador para o WampServer. Escolha o navegador de sua preferência, como o Google Chrome, procurando e selecionando o executável correspondente.

1.7. Em seguida, escolha o editor de texto que deseja usar. Neste exemplo, será utilizado o **Visual Studio Code**. Procure e selecione o executável do editor.

1.8. Clique em **Next** e depois em **Finish** para concluir a instalação do WampServer.

## 2. Configurando o Projeto Rio

### 2.1. Baixando o Arquivo ZIP do Projeto

Faça o download do arquivo ZIP do projeto diretamente do GitHub.

### 2.2. Extraindo o Projeto

Extraia o conteúdo do arquivo ZIP para a seguinte pasta:

```
C:\wamp64\www
```

### 2.3. Configurando o Banco de Dados

Para configurar o banco de dados do projeto, siga os passos abaixo:

1. Inicie o WampServer. Clique duas vezes no ícone do WampServer na área de trabalho e aguarde até que ele fique **verde** no canto inferior direito da tela, indicando que todos os serviços estão ativos.

2. Abra o navegador que você selecionou durante a instalação e acesse:

```
http://localhost/
```

3. Clique em **phpMyAdmin**.

4. Faça login no **phpMyAdmin** utilizando as credenciais padrão:
   - **Login**: `root`
   - **Senha**: *(deixe em branco)*

5. Clique em **Novo** para criar um novo banco de dados.

6. Insira o nome do banco de dados como `rio` e clique em **Criar**.

### 2.4. Importando o Banco de Dados

1. Com o banco de dados `rio` criado, selecione-o.

2. Clique na aba **Importar**.

3. Clique em **Escolher arquivo** e selecione o arquivo do banco de dados localizado na pasta extraída:

```
C:\wamp64\www\ProjetoRio-main\BANCO DE DADOS
```

4. Clique em **Abrir** e, em seguida, clique em **Executar** no final da página para importar o banco de dados.

## 3. Acessando a Aplicação

Após concluir todas as etapas acima, acesse o projeto no navegador utilizando o seguinte link:

```
http://localhost/ProjetoRio-main/
```

Agora você pode testar as funcionalidades do Projeto Rio.

> **Nota:** Este projeto ainda está em fase de criação e aperfeiçoamento. Contribuições são bem-vindas!

## Funcionalidades do Projeto Rio

### 3.1. CRUD de Pessoas

O Projeto Rio conta com um CRUD (Create, Read, Update, Delete) de pessoas, que permite cadastrar, visualizar, editar e excluir informações de pessoas físicas e jurídicas. Foi uma implementação desafiadora, pois estou aprendendo a usar o Adianti Framework, e muitas das funcionalidades foram desenvolvidas a partir de pesquisa e tentativa e erro.

### 3.2. Exemplo de Loja Virtual

Além do CRUD, o projeto também apresenta um exemplo de loja virtual simples, utilizando uma API pública para listar produtos. A integração com essa API inclui a exibição dos produtos em formato de cards, que mostram o título, a imagem e o preço do produto. Foi bastante complicado entender como realizar a integração correta, especialmente com a manipulação dos dados retornados pela API, mas isso foi um aprendizado valioso sobre como consumir APIs no contexto do Adianti Framework.

Espero que o guia e o resumo das funcionalidades ajudem a entender um pouco mais sobre o Projeto Rio!
