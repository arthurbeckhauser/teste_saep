CREATE DATABASE IF NOT EXISTS farmacia_reposicao
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE farmacia_reposicao;

-- Tabela de funcionários
CREATE TABLE funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL
);

-- Tabela de pedidos de reposição
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id INT NOT NULL,
    medicamento VARCHAR(150) NOT NULL,
    quantidade INT NOT NULL,
    categoria ENUM('generico', 'referencia', 'controlado', 'higiene') NOT NULL,
    urgencia ENUM('baixa', 'media', 'alta') NOT NULL,
    data_solicitacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('solicitado', 'em_separacao', 'recebido') NOT NULL DEFAULT 'solicitado',

    CONSTRAINT fk_pedido_funcionario
        FOREIGN KEY (funcionario_id)
        REFERENCES funcionarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT chk_quantidade
        CHECK (quantidade > 0)
);

-- 3 funcionários de teste
INSERT INTO funcionarios (nome, email) VALUES
('Arthur Fernandes', 'arthur@email.com'),
('Felipe Alves', 'felipe@email.com'),
('Bruno Dias', 'bruno@email.com');

-- 5 pedidos de teste
INSERT INTO pedidos
(funcionario_id, medicamento, quantidade, categoria, urgencia, status)
VALUES
(1, 'Paracetamol 750mg', 20, 'generico', 'alta', 'solicitado'),
(2, 'Dipirona 500mg', 30, 'generico', 'media', 'solicitado'),
(3, 'Amoxicilina 500mg', 15, 'controlado', 'alta', 'em_separacao'),
(1, 'Vitamina C', 25, 'referencia', 'baixa', 'em_separacao'),
(2, 'Álcool em Gel', 40, 'higiene', 'media', 'recebido');