<?php
class Usuario {
    // Atributos da classe
    public $nm_usuario;
    public $cd_cpf_usuario;
    public $nm_email_usuario;
    public $cd_senha_usuario;

    // Construtor com valores padrão
    public function __construct($nm_usuario = "", $cd_cpf_usuario = "", $nm_email_usuario = "", $cd_senha_usuario = "") {
        $this->nm_usuario = $nm_usuario;
        $this->cd_cpf_usuario = $cd_cpf_usuario;
        $this->nm_email_usuario = $nm_email_usuario;
        $this->cd_senha_usuario = $cd_senha_usuario;
    }

    // Método para exibir os dados do usuário (opcional)
    public function exibirDados() {
        return "Nome: " . $this->nm_usuario . ", CPF: " . $this->cd_cpf_usuario . ", Email: " . $this->nm_email_usuario;
    }
}
?>