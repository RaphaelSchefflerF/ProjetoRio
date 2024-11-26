<?php
class ProductListView extends TPage
{
    private $datagrid;

    public function __construct()
    {
        parent::__construct();

        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%; padding: 20px;';
        $vbox->add($this->criarMenu());
        $vbox->add($this->criarConteudo());
        
        parent::add($vbox);
    }

    private function criarMenu()
    {
        $menuPath = 'app/resources/menu.xml'; 
        $menu = new TMenu($menuPath); 
        return $menu;
    }

    private function criarConteudo()
    {
        $painel = new TPanelGroup('Produtos Disponíveis');
        $painel->style = 'margin: 20px;';

        
        $cards = $this->obterDadosDaApi();
        
        $container = new TElement('div');
        $container->style = 'display: flex; flex-wrap: wrap; gap: 20px; justify-content: space-around;';

        if ($cards) {
            foreach ($cards as $card) {
                $container->add($card);
            }
        }

        $painel->add($container);

        return $painel;
    }

    private function obterDadosDaApi()
    {
        try {
            
            $url = 'https://fakestoreapi.com/products';

            
            $response = file_get_contents($url);
            if ($response === false) {
                throw new Exception('Erro ao obter os dados da API');
            }

            $produtos = json_decode($response);

            
            $cards = [];
            foreach ($produtos as $produto) {
                $cards[] = $this->criarCardProduto($produto);
            }

            return $cards;
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            return null;
        }
    }

    private function criarCardProduto($produto)
    {
        
        $cotacaoDolar = $this->obterCotacaoDolar();
        $precoReais = number_format($produto->price * $cotacaoDolar, 2, ',', '.');

        
        $card = new TElement('div');
        $card->class = 'product-card';
        $card->style = 'border: 1px solid #ddd; padding: 15px; flex: 1 1 calc(33.333% - 20px); box-shadow: 2px 2px 5px rgba(0,0,0,0.1);';

        $img = new TImage($produto->image);
        $img->style = 'width: 100%; height: 200px; object-fit: cover; cursor: pointer;';
        $img->onclick = "showProductModal('" . addslashes(json_encode($produto)) . "')";
        $card->add($img);

        $title = new TElement('h3');
        $title->add($produto->title);
        $card->add($title);

        $price = new TElement('p');
        $price->style = 'font-weight: bold; font-size: 18px; color: #d9534f;';
        $price->add('Preço: R$ ' . $precoReais);
        $card->add($price);

        return $card;
    }

    private function obterCotacaoDolar()
    {
        
        $url = 'https://economia.awesomeapi.com.br/json/last/USD-BRL';

        
        $response = file_get_contents($url);
        if ($response === false) {
            throw new Exception('Erro ao obter a cotação do dólar');
        }

        $dados = json_decode($response);
        return floatval($dados->USDBRL->bid);
    }

    public function onShow()
    {
        
        $this->paginaAtual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $this->criarConteudo();
    }
}
?>

<script>
function showProductModal(produtoJson) {
    const produto = JSON.parse(produtoJson);
    const modalContent = `
        <div style="padding: 20px; background: #fff; border-radius: 10px; width: 400px;">
            <h2>${produto.title}</h2>
            <img src="${produto.image}" style="width: 100%; height: 300px; object-fit: cover; margin-bottom: 15px;" />
            <p><strong>Preço:</strong> R$ ${(produto.price * parseFloat(${obterCotacaoDolar()})).toFixed(2).replace('.', ',')}</p>
            <p><strong>Categoria:</strong> ${produto.category}</p>
            <p><strong>Descrição:</strong> ${produto.description}</p>
            <button onclick="closeProductModal()" style="padding: 10px 20px; background-color: #0062cc; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Fechar</button>
        </div>
    `;
    const modal = document.createElement('div');
    modal.id = 'productModal';
    modal.style = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center;';
    modal.innerHTML = modalContent;
    document.body.appendChild(modal);
}

function closeProductModal() {
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.remove();
    }
}
</script>

<style>
    body {
        background: #f4f6f9;
    }
    
    .product-card h3 {
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px;
        margin-bottom: 5px;
    }
    .product-card p {
        margin: 0;
        font-size: 14px;
    }
    button {
        padding: 10px 20px;
        margin: 5px;
        border: none;
        background-color: #0062cc;
        color: #fff;
        cursor: pointer;
        border-radius: 5px;
    }
    button:disabled {
        background-color: #ddd;
        cursor: not-allowed;
    }
</style>
