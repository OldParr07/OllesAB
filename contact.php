<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/members.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OllesAB - Kontakt</title>
        <link rel="stylesheet" href="style.css">
        <script src="https://kit.fontawesome.com/10235da58b.js" crossorigin="anonymous"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
        <?php if (isLoggedIn()): ?>
        <script>
            // Definiera funktioner endast om användaren är inloggad
            window.updateRoleField = function() {
                const rolePreset = document.getElementById('rolePreset');
                const customRoleGroup = document.getElementById('customRoleGroup');
                const roleInput = document.getElementById('role');
                
                if (rolePreset.value === 'custom') {
                    customRoleGroup.style.display = 'block';
                    roleInput.value = '';
                } else {
                    customRoleGroup.style.display = 'none';
                    roleInput.value = rolePreset.value;
                }
            };

            window.showAddForm = function() {
                document.getElementById("formTitle").textContent = "Lägg till medlem";
                document.getElementById("modalOverlay").classList.add("active");
                document.getElementById("memberForm").classList.add("active");
                document.getElementById("memberEditForm").reset();
                document.getElementById("memberId").value = "";
                document.getElementById("customRoleGroup").style.display = 'none';
                document.body.style.overflow = 'hidden'; // Förhindra scrollning av bakgrunden
            };

            window.hideForm = function() {
                document.getElementById("modalOverlay").classList.remove("active");
                document.getElementById("memberForm").classList.remove("active");
                document.body.style.overflow = ''; // Återaktivera scrollning
            };
            
            // Stäng modal när man klickar utanför
            window.addEventListener('click', function(e) {
                const modalOverlay = document.getElementById('modalOverlay');
                if (e.target === modalOverlay) {
                    hideForm();
                }
            });

            window.editMember = function(member) {
                try {
                    console.log('Editing member:', member);
                    // Om member är en sträng, parsa den som JSON
                    if (typeof member === 'string') {
                        member = JSON.parse(member);
                    }
                    
                    document.getElementById("formTitle").textContent = "Redigera medlem";
                    document.getElementById("memberId").value = member.id;
                    document.getElementById("name").value = member.name;
                    
                    // Sätt sektion
                    const sectionSelect = document.getElementById("section");
                    if (sectionSelect) {
                        sectionSelect.value = member.section || '';
                        console.log('Setting section to:', member.section);
                    }
                    
                    // Hantera roll
                    const rolePreset = document.getElementById("rolePreset");
                    const roleInput = document.getElementById("role");
                    const customRoleGroup = document.getElementById("customRoleGroup");
                    
                    // Sätt roll direkt i roleInput och visa customRoleGroup
                    rolePreset.value = 'custom';
                    customRoleGroup.style.display = 'block';
                    roleInput.value = member.role || '';
                    
                    document.getElementById("email").value = member.email || "";
                    document.getElementById("phone").value = member.phone || "";
                    
                    // Visa modalen
                    document.getElementById("modalOverlay").classList.add("active");
                    document.getElementById("memberForm").classList.add("active");
                    document.body.style.overflow = 'hidden';
                } catch (error) {
                    console.error('Error in editMember:', error);
                }
            };

            window.deleteMember = function(id) {
                if (!confirm("Är du säker på att du vill ta bort denna medlem?")) return;
                fetch("includes/members.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `action=delete&id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) window.location.reload();
                    else alert(data.message || "Ett fel uppstod");
                });
            };

            // Utloggningsfunktion
            window.logout = function() {
                fetch('includes/auth.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=logout'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            };

            // När dokumentet är klart
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById("memberEditForm");
                if (form) {
                    form.addEventListener("submit", async function(e) {
                        e.preventDefault();
                        console.log('Form submitted');

                        // Samla formulärdata
                        const formData = new FormData(this);
                        const id = formData.get("id");
                        const action = id ? "update" : "add";
                        
                        // Använd alltid värdet från role-input
                        const roleInput = document.getElementById('role');
                        if (roleInput) {
                            formData.set('role', roleInput.value);
                        }
                        
                        // Skapa URL-encoded data
                        const data = new URLSearchParams();
                        data.append("action", action);
                        for (let pair of formData.entries()) {
                            console.log(pair[0] + ': ' + pair[1]); // Debug utskrift
                            data.append(pair[0], pair[1]);
                        }
                        
                        try {
                            const response = await fetch("includes/members.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: data
                            });
                            
                            const responseData = await response.json();
                            console.log('Server response:', responseData); // Debug utskrift
                            
                            if (responseData.success) {
                                hideForm();
                                window.location.reload();
                            } else {
                                alert(responseData.message || "Ett fel uppstod");
                            }
                        } catch (error) {
                            console.error('Error submitting form:', error);
                            alert("Ett fel uppstod när formuläret skulle skickas");
                        }
                    });
                } else {
                    console.error('Could not find form element');
                }

                const menu = document.querySelector("#mobile-menu");
                const menuLinks = document.querySelector(".navbar__menu");
                menu?.addEventListener("click", function() {
                    menu.classList.toggle("is-active");
                    menuLinks.classList.toggle("active");
                });
            });
        </script>
        <?php endif; ?>
        <style>
            .admin-controls { margin: 1rem 0; padding: 1rem; background: #f5f5f5; border-radius: 4px; }
            .modal-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.7);
                z-index: 1000;
                display: flex;
                justify-content: center;
                align-items: center;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }
            .modal-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            .member-form {
                display: none;
                max-width: 600px;
                width: 90%;
                background: white;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                position: relative;
                transform: scale(0.7);
                opacity: 0;
                transition: transform 0.3s ease, opacity 0.3s ease;
            }
            .member-form.active {
                display: block;
                transform: scale(1);
                opacity: 1;
            }
            .close-modal {
                position: absolute;
                top: 1rem;
                right: 1rem;
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                color: #666;
                padding: 0.5rem;
                line-height: 1;
            }
            .close-modal:hover {
                color: #333;
            }
            .form-group { 
                margin-bottom: 1.5rem; 
                position: relative;
            }
            .form-group label { 
                display: block; 
                margin-bottom: 0.5rem;
                color: #005792;
                font-weight: 500;
                font-size: 0.95rem;
            }
            .form-group input, 
            .form-group textarea,
            .form-group select { 
                width: 100%; 
                padding: 0.75rem 1rem;
                border: 2px solid #e0e0e0;
                border-radius: 8px;
                font-size: 1rem;
                transition: all 0.3s ease;
                background-color: #f8f9fa;
                color: #333;
            }
            .form-group input:focus, 
            .form-group textarea:focus,
            .form-group select:focus { 
                outline: none;
                border-color: #005792;
                background-color: #fff;
                box-shadow: 0 0 0 4px rgba(0, 87, 146, 0.1);
            }
            .form-group select {
                appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 1rem center;
                padding-right: 2.5rem;
            }
            .edit-buttons { 
                display: none; 
            }
            .logged-in .edit-buttons { 
                display: flex; 
                gap: 0.5rem; 
                margin-top: 0.75rem;
            }
            .btn-edit, .btn-delete { 
                padding: 0.5rem 1rem;
                font-size: 0.875rem; 
                border-radius: 6px; 
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .btn-edit { 
                background: #4CAF50; 
                color: white; 
                border: none;
            }
            .btn-edit:hover {
                background: #45a049;
                transform: translateY(-1px);
            }
            .btn-delete { 
                background: #f44336; 
                color: white; 
                border: none;
            }
            .btn-delete:hover {
                background: #e53935;
                transform: translateY(-1px);
            }
            /* Form Actions */
            .form-actions {
                display: flex;
                gap: 1rem;
                margin-top: 2rem;
                padding-top: 1.5rem;
                border-top: 1px solid #eee;
            }
            .form-actions button {
                flex: 1;
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .form-actions .btn--primary {
                background: #005792;
                color: white;
                border: none;
            }
            .form-actions .btn--primary:hover {
                background: #004a7c;
                transform: translateY(-1px);
            }
            .form-actions .btn--secondary {
                background: #fff;
                color: #666;
                border: 2px solid #ddd;
            }
            .form-actions .btn--secondary:hover {
                border-color: #999;
                color: #333;
            }
            /* Modal Header Styling */
            .member-form h3 {
                color: #005792;
                font-size: 1.5rem;
                margin: 0 0 1.5rem 0;
                padding-bottom: 1rem;
                border-bottom: 2px solid #f0f0f0;
            }
            
            /* Ny grid och sektionsstilar */
            .members-section {
                margin-bottom: 2rem;
                padding: 1rem;
                background: #f9f9f9;
                border-radius: 8px;
            }
            .section-title {
                color: #005792;
                font-size: 1.5rem;
                margin-bottom: 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 2px solid #005792;
            }
            .members-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 1rem;
                margin-top: 1rem;
            }
            .contact-card {
                background: white;
                padding: 1.5rem;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .contact-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            }
            .contact-role {
                color: #666;
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }
            .contact-name {
                font-size: 1.1rem;
                font-weight: bold;
                margin-bottom: 1rem;
                color: #333;
            }
            .contact-meta {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            .contact-link {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: #005792;
                text-decoration: none;
                font-size: 0.9rem;
            }
            .contact-link:hover {
                color: #003d5c;
            }
            .contact-link i {
                width: 20px;
            }
        </style>
    </head>

    <body class="<?php echo isLoggedIn() ? 'logged-in' : ''; ?>">
        <nav class="navbar">
            <div class="navbar__container">
                <a href="index.php" class="navbar__logo"><img src="images/OllLogo.gif" alt="OllesAB Logo" class="navbar__logo-image"></a>
                <div class="navbar__toggle" id="mobile-menu"><span class="bar"></span><span class="bar"></span><span class="bar"></span></div>
                <div class="navbar__menu">
                    <a href="index.php" class="navbar__link">Hem</a>
                    <a href="about.php" class="navbar__link">Om oss</a>
                    <a href="calendar.php" class="navbar__link">Aktiviteter</a>
                    <a href="contact.php" class="navbar__link active">Kontakt</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="#" class="navbar__link" onclick="logout(); return false;">Logga ut</a>
                    <?php else: ?>
                        <a href="login.php" class="navbar__link">Logga in</a>
                    <?php endif; ?>
                    <a href="#" class="btn btn--primary">Bli medlem</a>
                </div>
            </div>
        </nav>

        <main class="container">
            <header class="page-header">
                <h1>Kontaktpersoner & funktionärer</h1>
                <p class="lead">Här hittar du kontaktuppgifter till styrelse och funktionärer.</p>
            </header>

            <?php if (isLoggedIn()): ?>
            <div class="admin-controls">
                <button class="btn btn--primary" onclick="showAddForm()">Lägg till ny medlem</button>
                <div id="modalOverlay" class="modal-overlay">
                    <div id="memberForm" class="member-form">
                        <button type="button" class="close-modal" onclick="hideForm()">&times;</button>
                        <h3 id="formTitle">Lägg till medlem</h3>
                        <form id="memberEditForm">
                        <input type="hidden" id="memberId" name="id">
                        <div class="form-group">
                            <label for="section">Sektion:</label>
                            <select id="section" name="section" required>
                                <option value="Styrelse">Styrelse</option>
                                <option value="Revisor">Revisor</option>
                                <option value="Valberedning">Valberedning</option>
                                <option value="Materialförvaltare">Materialförvaltare</option>
                                <option value="Säkerhet">Säkerhet</option>
                                <option value="Utbildning övrigt">Utbildning övrigt</option>
                                <option value="Bidrag">Bidrag</option>
                                <option value="Webben">Webben</option>
                                <option value="Badhuset nyck. tagg.">Badhuset nyck. tagg.</option>
                                <option value="Badhus nyckel tagg">Badhus nyckel tagg</option>
                                <option value="Föreningsmöte Badhus">Föreningsmöte Badhus</option>
                                <option value="Lokalen">Lokalen</option>
                                <option value="Fridykning">Fridykning</option>
                                <option value="Sportdykning">Sportdykning</option>
                                <option value="HLR">HLR</option>
                                <option value="Dykaktiviteter">Dykaktiviteter</option>
                                <option value="Gabbes fridykarläger">Gabbes fridykarläger</option>
                                <option value="Dykläger övrigt">Dykläger övrigt</option>
                                <option value="Badplatsrensning Karlskoga">Badplatsrensning - Karlskoga</option>
                                <option value="Badplatsrensning Degerfors">Badplatsrensning - Degerfors</option>
                                <option value="Badplatsrensning Kristinehamn">Badplatsrensning - Kristinehamn</option>
                                <option value="Övriga aktiviteter">Övriga aktiviteter</option>
                                <option value="Övriga kunskaper">Övriga kunskaper</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">Roll:</label>
                            <select id="rolePreset" name="rolePreset" onchange="updateRoleField()">
                                <option value="">Välj roll eller skriv egen...</option>
                                <option value="Ordförande">Ordförande</option>
                                <option value="Vice ordförande">Vice ordförande</option>
                                <option value="Sekreterare">Sekreterare</option>
                                <option value="Kassör">Kassör</option>
                                <option value="Ledamot">Ledamot</option>
                                <option value="Suppleant">Suppleant</option>
                                <option value="Revisor">Revisor</option>
                                <option value="Instruktör">Instruktör</option>
                                <option value="Badhusansvar">Badhusansvar</option>
                                <option value="custom">Annan roll...</option>
                            </select>
                        </div>
                        <div class="form-group" id="customRoleGroup" style="display: none;">
                            <label for="role">Egen roll:</label>
                            <input type="text" id="role" name="role" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Namn:</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-post:</label>
                            <input type="email" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="phone">Telefon:</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn--primary">Spara</button>
                            <button type="button" class="btn btn--secondary" onclick="hideForm()">Avbryt</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div id="membersList">
                <?php 
                $members = getAllMembers();
                $currentSection = '';
                foreach ($members as $member) :
                    if ($currentSection !== $member['section']) :
                        if ($currentSection !== '') {
                            echo '</div></section>'; // Stäng föregående grid och sektion
                        }
                        $currentSection = $member['section'];
                        echo '<section class="members-section">';
                        echo '<h2 class="section-title">' . htmlspecialchars($currentSection) . '</h2>';
                        echo '<div class="members-grid">';
                    endif;
                ?>
                    <article class="contact-card">
                        <div class="contact-role"><?= htmlspecialchars($member["role"]) ?></div>
                        <div class="contact-name"><?= htmlspecialchars($member["name"]) ?></div>
                        <div class="contact-meta">
                            <?php if (!empty($member["phone"])): ?><a class="contact-link" href="tel:<?= preg_replace('/[^0-9+]/',"",$member["phone"]) ?>"><i class="fas fa-phone"></i><span><?= htmlspecialchars($member["phone"]) ?></span></a><?php endif; ?>
                            <?php if (!empty($member["email"])): ?><a class="contact-link" href="mailto:<?= htmlspecialchars($member["email"]) ?>"><i class="fas fa-envelope"></i><span><?= htmlspecialchars($member["email"]) ?></span></a><?php endif; ?>
                        </div>
                        <?php if (isLoggedIn()): ?>
                            <?php if (isLoggedIn()): ?>
                            <div class="edit-buttons">
                                <button class="btn-edit" 
                                    data-member='<?= htmlspecialchars(json_encode($member), ENT_QUOTES) ?>'
                                    onclick="editMember(this.dataset.member)">
                                    <i class="fas fa-edit"></i> Redigera
                                </button>
                                <button class="btn-delete" onclick="deleteMember(<?= $member["id"] ?>)"><i class="fas fa-trash"></i> Ta bort</button>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </article>
                <?php 
                endforeach;
                if ($currentSection !== '') {
                    echo '</div></section>'; // Stäng sista grid och sektion
                }
                ?>
            </div>
        </main>

        <?php include "includes/footer.php"; ?>
    </body>
</html>