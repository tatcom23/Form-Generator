<?php
class Pergunta {
    private $id;
    private $descricao;
    private $formularioId;
    private $categoriaId;
    private $tipoPerguntaId;

    // Construtor
    public function __construct($descricao, $formularioId, $categoriaId, $tipoPerguntaId, $id = null) {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->formularioId = $formularioId;
        $this->categoriaId = $categoriaId;
        $this->tipoPerguntaId = $tipoPerguntaId;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getFormularioId() {
        return $this->formularioId;
    }

    public function getCategoriaId() {
        return $this->categoriaId;
    }

    public function getTipoPerguntaId() {
        return $this->tipoPerguntaId;
    }

    // Métodos para salvar no banco de dados
    public function salvar($conn) {
        $sql = "INSERT INTO PERGUNTA (ds_pergunta, FORMULARIO_id_formulario, CATEGORIA_id_categoria, TIPO_PERGUNTA_id_tipo_pergunta)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siii", $this->descricao, $this->formularioId, $this->categoriaId, $this->tipoPerguntaId);
        return $stmt->execute();
    }

    // Método para listar perguntas de um formulário
    public static function listarPorFormulario($conn, $formularioId) {
        $sql = "SELECT p.id_pergunta, p.ds_pergunta, c.nm_categoria, tp.nm_tipo_pergunta 
                FROM PERGUNTA p
                LEFT JOIN CATEGORIA c ON p.CATEGORIA_id_categoria = c.id_categoria
                LEFT JOIN TIPO_PERGUNTA tp ON p.TIPO_PERGUNTA_id_tipo_pergunta = tp.id_tipo_pergunta
                WHERE p.FORMULARIO_id_formulario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $formularioId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>