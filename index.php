<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Brow Designer - Plataforma de Cursos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/home.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark pt-4 pb-4">
    <div class="container">
      <a class="navbar-brand" href="#">Brow Designer</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#cursos">Cursos</a></li>
          <li class="nav-item"><a class="nav-link" href="#sobre">Quem sou eu</a></li>
          <li class="nav-item"><a class="btn btn-gold ms-2" href="cadastro.php">Inscreva-se</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section class="hero">
    <h1>Curso de Design de Sobrancelhas</h1>
    <p class="lead">Torne-se uma profissional de excelência com técnicas modernas e exclusivas.</p>
  </section>

  <!-- Cursos -->
  <section class="py-5">
    <div class="container">
      <h2 class="fw-bold text-center mb-5">O que você vai aprender</h2>
      <div class="row g-4">
        <div class="col-md-4 text-center">
          <img src="../brow-designer/pinceis.jpg"
               class="img-fluid rounded-circle mb-3 shadow w-50 img-thumbnail" alt="Materiais">
          <h5 class="fw-bold">Técnicas Avançadas</h5>
          <p>Aprenda métodos modernos de design para realçar a beleza natural de cada cliente.</p>
        </div>
        <div class="col-md-4 text-center">
          <img src="../brow-designer/pinceis.jpg"
               class="img-fluid rounded-circle mb-3 shadow w-50 img-thumbnail" alt="Materiais">
          <h5 class="fw-bold">Ferramentas de Qualidade</h5>
          <p>Descubra como escolher materiais adequados para obter resultados impecáveis.</p>
        </div>
        <div class="col-md-4 text-center">
          <img src="../brow-designer/pinceis.jpg"
               class="img-fluid rounded-circle mb-3 shadow w-50 img-thumbnail" alt="Materiais">
          <h5 class="fw-bold">Olhar crítico</h5>
          <p>Desenvolva a capacidade de analisar cada rosto com atenção aos detalhes.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Quem Sou Eu -->
  <section id="sobre" class="about mt-5">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-5">
          <img src="https://images.unsplash.com/photo-1596464716124-8b4d8e6ebd8c" class="img-fluid rounded shadow" alt="Instrutora">
        </div>
        <div class="col-md-7">
          <h2 style="color:#a8796a;">Quem Sou Eu</h2>
          <p>Sou <strong>Marilia Vendrame</strong>, profissional da área da beleza há 4
            anos, sempre me especializando e pensando em trazer o melhor para minhas alunas e minhas clientes. Iniciei minha jornada em Indaiatuba (SP),
            fidelizando 192 clientes. E agora, a paulista morando em solo carioca, está recomeçando com propósito a fidelização de novas clientes!</p>
          <p>Também leciono cursos presenciais, para quem deseja iniciar ou aperfeiçoar suas técnicas de design com henna, brow lamination e lash lifting.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Inscrição -->
  <section id="inscricao" class="container text-center my-5">
    <h2 class="section-title">Garanta sua Vaga!</h2>
    <p>Entre em contato para informações sobre datas, valores e formas de pagamento.</p>
    <a href="https://wa.me/5599999999999" target="_blank" class="btn btn-success btn-lg">Falar no WhatsApp</a>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Brow Designer - Todos os direitos reservados.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
