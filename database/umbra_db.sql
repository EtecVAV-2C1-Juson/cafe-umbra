-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23/06/2026 às 09:34
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12


CREATE DATABASE IF NOT EXISTS umbra_db;
USE umbra_db;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `umbra_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `slug`) VALUES
(1, 'Cafés Tradicionais', 'cafes-tradicionais'),
(2, 'Chás', 'chas'),
(3, 'Cafés Gourmets', 'cafes-gourmets'),
(4, 'Bebidas', 'bebidas'),
(5, 'Cookies', 'cookies'),
(6, 'Salgados', 'salgados');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nome`, `email`, `senha`, `criado_em`) VALUES
(1, 'Julia Polycarpo', 'juliaapolycarpo@gmail.com', '1234', '2026-06-23 05:13:24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id_item` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `nome_produto` varchar(100) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`id_item`, `id_pedido`, `nome_produto`, `quantidade`, `preco_unitario`) VALUES
(1, 1, 'Cappuccino Clássico', 1, 14.00),
(2, 1, 'Café Expresso', 1, 7.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `horario_retirada` time NOT NULL,
  `total_pedido` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status_pedido` enum('Pendente','Em preparo','Pronto','Finalizado') DEFAULT 'Pendente',
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_cliente`, `horario_retirada`, `total_pedido`, `status_pedido`, `data_pedido`) VALUES
(1, 1, '11:58:00', 21.00, 'Em preparo', '2026-06-23 03:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(50) NOT NULL,
  `quantidade_medida` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `categoria_id`, `nome`, `descricao`, `preco`, `imagem`, `quantidade_medida`) VALUES
(1, 1, 'Café Americano', 'O clássico café expresso diluído em água quente, resultando em uma bebida suave e encorpada.', 8.50, 'tam.png', '240ml'),
(2, 1, 'Café com Chantilly', 'Expresso intenso coberto com uma generosa camada de chantilly artesanal levemente adocicado.', 12.90, 'tch.png', '180ml'),
(3, 1, 'Café com Leite', 'O tradicional pingado brasileiro: café expresso combinado perfeitamente com leite vaporizado.', 9.00, 'tcl.png', '240ml'),
(4, 1, 'Cappuccino Clássico', 'Dose de expresso, leite vaporizado e uma cremosa espuma de leite, finalizado com polvilho de cacau.', 14.00, 'tcp.png', '200ml'),
(5, 1, 'Café Caseiro', 'Café passado na hora com aquele gostinho nostálgico de carinho e aconchego familiar.', 6.00, 'tct.png', '150ml'),
(6, 1, 'Café Expresso', 'Extração pura e concentrada de grãos selecionados, com crema densa e sabor marcante.', 7.00, 'tex.png', '50ml'),
(7, 1, 'Latte de Baunilha', 'Uma combinação reconfortante de café expresso, leite vaporizado e um toque suave de xarope de baunilha.', 13.50, 'tlb.png', '300ml'),
(8, 2, 'Chá de Camomila e Mel', 'Infusão relaxante de flores de camomila natural enriquecida com notas doces de mel silvestre.', 9.50, 'hcm.png', '250ml'),
(9, 2, 'Chá de Frutas Vermelhas', 'Blend aromático e levemente cítrico de hibisco, amora, morango e framboesa servido quente.', 10.50, 'hfv.png', '250ml'),
(10, 2, 'Chá de Maçã com Canela', 'Mistura aconchegante de pedaços de maçã desidratada e especiarias, com perfume marcante de canela.', 9.50, 'hmc.png', '250ml'),
(11, 2, 'Mate Batido com Limão', 'Chá mate artesanal batido com gelo e suco de limão fresco, extremamente refrescante.', 8.90, 'hml.png', '400ml'),
(12, 2, 'Chá Preto de Pêssego', 'Infusão encorpada de folhas de chá preto com o aroma doce e frutado de pêssegos selecionados.', 10.00, 'hpp.png', '300ml'),
(13, 2, 'Chá Verde com Limão e Hortelã', 'Bebida leve e antioxidante combinando chá verde, rodelas de limão e folhas frescas de hortelã.', 9.00, 'hvh.png', '300ml'),
(14, 3, 'Iced Caramel Macchiato', 'Café gelado cremoso com leite, dose de expresso e uma finalização generosa de calda de caramelo.', 16.90, 'gcm.png', '400ml'),
(15, 3, 'Frappé de Cookies & Cream', 'Bebida super gelada batida com base cremosa, pedaços de biscoito black e chantilly no topo.', 18.90, 'gcc.png', '450ml'),
(16, 3, 'Frappé de Caramelo', 'Mistura irresistível de café, leite e caramelo batidos com gelo, decorado com calda artesanal.', 17.90, 'gfc.png', '450ml'),
(17, 3, 'Latte de Canela e Mel', 'Café expresso com leite vaporizado, adoçado com mel puro e polvilhado sutilmente com canela.', 14.50, 'glc.png', '300ml'),
(18, 3, 'Mocha de Chocolate Fudge', 'Uma camada rica de calda de chocolate, café expresso e leite vaporizado, coberto com espuma.', 15.90, 'gmc.png', '280ml'),
(19, 4, 'Água Mineral com Gás', 'Água mineral natural levemente gaseificada, ideal para limpar o paladar antes do café.', 5.00, 'bag.png', '350ml'),
(20, 4, 'Água Mineral Natural', 'Água mineral refrescante sem gás para hidratação em qualquer hora do dia.', 4.50, 'ban.png', '350ml'),
(21, 4, 'Coca-Cola Original', 'A tradicional bebida refrescante em lata servida trincando de gelada.', 6.50, 'bcn.png', '350ml'),
(22, 4, 'Coca-Cola Zero', 'Sabor tradicional da Coca-Cola com zero açúcares e calorias, servida com gelo.', 6.50, 'bcz.png', '350ml'),
(23, 4, 'Fanta Laranja', 'Refrigerante sabor laranja super refrescante e gasoso servido em lata.', 6.00, 'bfl.png', '350ml'),
(24, 4, 'Guaraná Antarctica', 'Refrigerante brasileiro clássico feito com o extrato natural do fruto do guaraná.', 6.00, 'bga.png', '350ml'),
(25, 4, 'Suco Natural de Morango', 'Suco feito puramente com morangos frescos selecionados, batido na hora.', 9.90, 'bmg.png', '400ml'),
(26, 4, 'Suco Natural de Maracujá', 'Suco natural relaxante batido diretamente com a polpa do fruto fresco.', 9.50, 'bmj.png', '400ml'),
(27, 4, 'Pink Lemonade Artesanal', 'Bebida charmosa feita com suco de limão siciliano espremido e um toque de xarope de cranberry.', 11.90, 'bpl.png', '450ml'),
(28, 4, 'Suco Natural de Laranja', 'Suco integral 100% puro espremido direto da fruta, sem adição de água ou açúcar.', 9.00, 'bsl.png', '400ml'),
(29, 5, 'Cookie Clássico com Gotas', 'Massa amanteigada tradicional de receita americana, crocante por fora e macia com gotas de chocolate ao leite.', 8.50, 'ccc.png', '90g'),
(30, 5, 'Cookie de Creme de Avelã', 'Cookie de baunilha recheado generosamente com Nutella legítima, derretendo a cada mordida.', 11.00, 'cac.png', '100g'),
(31, 5, 'Cookie Double Avelã', 'Massa com cacau em pó black cravejada de pedaços crocantes de avelãs torradas e chocolate.', 10.00, 'cav.png', '90g'),
(32, 5, 'Cookie de Doce de Leite', 'Massa dourada recheada com doce de leite cremoso padrão mineiro e pitada de flor de sal.', 10.50, 'cdl.png', '100g'),
(33, 5, 'Cookie Red Velvet', 'A icônica massa vermelha aveludada recheada com gotas nobres de chocolate branco puro.', 11.50, 'crv.png', '95g'),
(34, 5, 'Cookie Triple Chocolate', 'O paraíso dos chocólatras: combinação de massa de cacau com gotas de chocolate amargo, ao leite e branco.', 9.90, 'ctc.png', '90g'),
(35, 6, 'Croissant de Chocolate', 'Massa folhada francesa super leve e amanteigada recheada com bastões de chocolate meio amargo.', 13.90, 'scc.png', '120g'),
(36, 6, 'Croissant de Presunto e Queijo', 'Folhado clássico quentinho recheado com uma generosa camada de presunto magro e muçarela derretida.', 14.50, 'scp.png', '140g'),
(37, 6, 'Empada de Frango Cremoso', 'Massa podre tradicional que derrete na boca com recheio de peito de frango desfiado e requeijão.', 8.00, 'sef.png', '110g'),
(38, 6, 'Folhado de Frango com Requeijão', 'Massa folhada dourada e crocante recheada com frango temperado com ervas finas e catupiry.', 9.50, 'sff.png', '130g'),
(39, 6, 'Misto Quente Especial', 'Pão de miga artesanal tostado na chapa com manteiga, recheado com queijo e presunto.', 10.00, 'smq.png', '150g'),
(40, 6, 'Pão de Queijo Mineiro', 'Feito com polvilho selecionado e queijo canastra real, crocante por fora e puxa-puxa por dentro.', 4.50, 'spq.png', '60g');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
