<section class="contact-section" id="contact">
    <div class="contact-section-row-1">
        <div class="contact-left">
            <h2>¿Necesitas ayuda?</h2>
            <p>Estamos aquí para ayudarte. Si buscas automatizar procesos, optimizar tareas repetitivas o simplemente necesitas asesoría técnica, completa el formulario y te responderemos a la brevedad. Diseñamos automatizaciones a medida para que tu negocio funcione de forma más eficiente.</p>
            <img src="img/robot-talk-1.png" alt="Contacto" />
        </div>
        <div class="contact-right">
            <form id="contactForm" action="php/contact_process.php" method="POST">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required />
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required />
                <label for="message">Mensaje:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
                <button type="submit" class="btn-primary">Enviar</button>
            </form>
        </div>
    </div>
    <div class="contact-section-row-2">
        <div class="form-alert">
            <?php if (isset($_SESSION['form_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    <?php
                    echo htmlspecialchars($_SESSION['form_success']);
                    unset($_SESSION['form_success']); // Limpiar sesión
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['form_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
                    <?php
                    echo htmlspecialchars($_SESSION['form_error']);
                    unset($_SESSION['form_error']); // Limpiar sesión
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>