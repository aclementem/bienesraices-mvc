<main class="contenedor seccion">
    <h1>Más Sobre Nosotros</h1>

    <?php include 'iconos.php' ?>

</main>

<section class="seccion contenedor">
    <h2>Casas y Depas en Venta</h2>

    <?php

    include 'listado.php';

    ?>

    <div class="alinear-derecha">
        <a href="anuncios.php" class="boton-verde">Ver Todas</a>
    </div>
</section>

<section class="imagen-contacto">
    <h2>Encuentra la casa de tus sueños</h2>
    <p>
        Llena el formulario de contacto y un asesor se pondrá en contacto
        contigo para más información
    </p>
    <a href="contacto.php" class="boton-amarillo">Contáctanos</a>
</section>

<div class="contenedor seccion seccion-inferior">
    <section class="blog">
        <h3>Nuestro Blog</h3>
        <article class="entrada-blog">
            <div class="imagen">
                <picture>
                    <source srcset="build/img/blog1.webp" type="image/webp" />
                    <source srcset="build/img/blog1.jpg" type="image/jpg" />
                    <img loading="lazy" src="build/img/blog1.jpg" alt="Imagen entrada Blog" />
                </picture>
            </div>
            <div class="texto-entrada">
                <a href="entrada.php">
                    <h4>Terraza en el techo de tu casa</h4>
                    <p>Escrito el: <span>20-11-2021</span> por: <span>Admin</span></p>
                    <p>
                        Consejos para construir una terraza en el techo de tu casa con
                        los mejores materiales y ahorrar dinero
                    </p>
                </a>
            </div>
        </article>
        <article class="entrada-blog">
            <div class="imagen">
                <picture>
                    <source srcset="build/img/blog2.webp" type="image/webp" />
                    <source srcset="build/img/blog2.jpg" type="image/jpg" />
                    <img loading="lazy" src="build/img/blog2.jpg" alt="Imagen entrada Blog" />
                </picture>
            </div>
            <div class="texto-entrada">
                <a href="entrada.php">
                    <h4>Guia para decoración de tu hogar</h4>
                    <p class="informacion-meta">
                        Escrito el: <span>24-10-2021</span> por: <span>Admin</span>
                    </p>
                    <p>Consejos de decoración para tu hogar</p>
                </a>
            </div>
        </article>
    </section>
    <section class="testimoniales">
        <h3>Testimonios</h3>
        <div class="testimonial">
            <blockquote>
                Lorem Ipsum Lorem IpsumLorem IpsumLorem IpsumLorem IpsumLorem
                IpsumLorem IpsumLorem IpsumLoremIpsumLorem IpsumLorem IpsumLorem
                Ipsum
            </blockquote>
            <p>- Alex Clemente -</p>
        </div>
    </section>
</div>