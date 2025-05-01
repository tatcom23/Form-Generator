<?php
class Formulario {
    private $id;
    private $nome;
    private $dataInicio;
    private $dataFim;
    private $idUsuario;

    // Construtor
    public function __construct($nome, $dataInicio, $dataFim, $idUsuario, $id = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->dataInicio = $dataInicio;
        $this->dataFim = $dataFim;
        $this->idUsuario = $idUsuario;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function getDataFim() {
        return $this->dataFim;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    // Métodos para salvar no banco de dados
    public function salvar($conn) {
        $sql = "INSERT INTO FORMULARIO (nm_formulario, dt_inicio_formulario, dt_fim_formulario, USUARIO_id_usuario)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta: " . $conn->error);
        }

        $stmt->bind_param("sssi", $this->nome, $this->dataInicio, $this->dataFim, $this->idUsuario);
        return $stmt->execute();
    }

    // Método para listar formulários de um usuário
    public static function listarPorUsuario($conn, $idUsuario) {
        $sql = "SELECT id_formulario, nm_formulario, dt_inicio_formulario, dt_fim_formulario FROM FORMULARIO WHERE USUARIO_id_usuario = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Erro ao preparar a consulta: " . $conn->error);
        }

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>