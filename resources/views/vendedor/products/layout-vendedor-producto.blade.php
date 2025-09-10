@extends('layouts.app')

@section('title', "Cargar Producto - {{ env('APP_NAME', 'CaballosApp') }}")

@section('content')
<div class="contact-page">
    

    <!-- Main Content -->
    <section class="contact-content">
        <div class="container py-4">
            <div class="main-container">
                @yield('sub-content')
            </div>
        </div>
    </section>
</div>
<style>
.contact-page {
    font-family: 'Inter', sans-serif;
}

.contact-hero {
    position: relative;
    height: 50vh;
    min-height: 400px;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-bg-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #8B4513;
}

.hero-title {
    font-size: 3rem;
    font-weight: 900;
    color: white;
    margin-bottom: 20px;
    text-shadow: 0 4px 20px #DEB887;
    position: relative;
    z-index: 2;
}

.hero-subtitle {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.95);
    position: relative;
    z-index: 2;
    font-weight: 300;
}

.contact-content {
    padding: 80px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.info-content {
    padding-right: 40px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #101820;
    margin-bottom: 30px;
    line-height: 1.2;
}

.section-description {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #555;
    margin-bottom: 40px;
}

.services-list {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.service-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.service-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 169, 224, 0.15);
}

.service-icon {
    font-size: 2.5rem;
    flex-shrink: 0;
}

.service-text h4 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #DEB887;
    margin-bottom: 10px;
}

.service-text p {
    color: #666;
    line-height: 1.5;
    margin: 0;
}

.contact-info-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 169, 224, 0.15);
}

.info-icon {
    background: linear-gradient(135deg, #DEB887, #DEB887);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.info-details h5 {
    color: #101820;
    font-weight: 700;
    margin-bottom: 5px;
    font-size: 1rem;
}

.info-details a,
.info-details span {
    color: #666;
    text-decoration: none;
    font-size: 0.9rem;
    line-height: 1.4;
}

.info-details a:hover {
    color: #8B4513;
}

.contact-form-container {
    background: linear-gradient(135deg, #8B4513 0%, #8B4513 100%);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.form-header {
    text-align: center;
    margin-bottom: 40px;
}

.form-header h3 {
    color: white;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 20px;
}

.form-header p {
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.6;
    font-size: 0.95rem;
}

.contact-form .form-label {
    color: #fff;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.contact-form .form-control {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    color: white;
    padding: 12px 16px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.contact-form .form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.contact-form .form-control:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: #DEB887;
    box-shadow: 0 0 0 0.2rem rgba(0, 169, 224, 0.25);
    color: white;
}

.phone-input {
    display: flex;
    gap: 10px;
}

.country-code {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    color: white;
    padding: 12px;
    width: 110px;
    font-size: 0.85rem;
    appearance: none; /* quita estilos nativos */
    -webkit-appearance: none;
    -moz-appearance: none;
}

/* Forzar opciones con fondo oscuro y texto claro */
.country-code option {
    background: #8B4513 !important; /* marrón oscuro */
    color: white !important;
}
.select-fondo {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    color: white;
    appearance: none; /* quita estilos nativos */
    -webkit-appearance: none;
    -moz-appearance: none;
}

/* Forzar opciones con fondo oscuro y texto claro */
.select-fondo option {
    background: #8B4513 !important; /* marrón oscuro */
    color: white !important;
}

.phone-number {
    flex: 1;
}

.whatsapp-option {
    background: #DEB887;
    border: 1px solid #DEB887;
    border-radius: 10px;
    padding: 15px;
}


.form-check-input:checked {
    background-color: #DEB887;
    border-color: #DEB887;
}

.submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #DEB887, #DEB887);
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px #DEB887;
}

.submit-btn:hover {
    background: linear-gradient(135deg, #DEB887, #DEB887);
    transform: translateY(-3px);
    box-shadow: 0 12px 35px #DEB887;
}

.whatsapp-btn {
    display: block;
    width: 100%;
    background: #25d366;
    color: white;
    text-decoration: none;
    padding: 12px 20px;
    border-radius: 25px;
    text-align: center;
    font-weight: 600;
    transition: all 0.3s ease;
}

.whatsapp-btn:hover {
    background: #128c7e;
    color: white;
    transform: translateY(-2px);
}

.alert-success {
    background: #28a745;
    border: 2px solid #28a745;
    color: white;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 30px;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.invalid-feedback {
    color: #ff6b6b;
    font-size: 0.85rem;
    margin-top: 5px;
}

/* Responsive */
@media (max-width: 992px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .section-title {
        font-size: 2.2rem;
    }
    
    .info-content {
        padding-right: 0;
        margin-bottom: 40px;
    }
    
    .contact-form-container {
        padding: 30px;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .contact-content {
        padding: 60px 0;
    }
    
    .services-list {
        gap: 25px;
    }
    
    .service-item {
        gap: 15px;
        padding: 15px;
    }
    
    .service-icon {
        font-size: 2rem;
    }
    
    .contact-form-container {
        padding: 25px;
    }
    
    .form-header h3 {
        font-size: 1.6rem;
    }

    .contact-info-cards {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.8rem;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .phone-input {
        flex-direction: column;
        gap: 15px;
    }
    
    .country-code {
        width: 100%;
    }

    .service-item {
        flex-direction: column;
        text-align: center;
    }

    .info-card {
        flex-direction: column;
        text-align: center;
    }
}



/* Asegurar que el contenedor del formulario tenga fondo */
.form-container, .main-content {
    background: #FAF9F6;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #DEB887;
}

/* Dark mode amigable dentro del editor */
.ck-content {
  background: #FAF9F6 !important;
  color: #101820 !important;
}
.ck.ck-editor__main>.ck-editor__editable {
  border-color: #DEB887 !important;
}
.ck.ck-toolbar {
  background: #FAF9F6 !important;
  border-color: #DEB887 !important;
}
.ck.ck-button, .ck.ck-toolbar__separator {
  filter: brightness(0.9);
}
.btn-success { background-color: #DEB887; border-color: #DEB887; }
.btn-success:hover { background-color: #f7a831; border-color: #f7a831; color: #101820; }
</style>
@endsection