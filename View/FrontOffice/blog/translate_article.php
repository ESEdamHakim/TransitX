<div style="text-align: center; margin-top: 30px;">
    <label for="languageSelect">Choisir la langue :</label>
    <select id="languageSelect">
        <option value="fr|en">Anglais</option>
        <option value="fr|es">Espagnol</option>
        <option value="fr|de">Allemand</option>
        <option value="fr|it">Italien</option>
        <option value="fr|pt">Portugais</option>
    </select>
    <button id="translateButton" onclick="translateArticle()">
        <i class="fa fa-language"></i> Traduire cet article
    </button>
</div>

<script>
async function translateArticle() {
  const button = document.getElementById('translateButton');
  button.disabled = true;
  button.innerHTML = "Traduction en cours... <span class='spinner'></span>";

  const selectedLanguage = document.getElementById('languageSelect').value;

  const textElements = document.querySelectorAll('.content p, .content h3, .social-share p, #articleTitle');

  const translationPromises = Array.from(textElements).map(async (el) => {
    // Sauvegarder le texte original s’il n’est pas encore sauvegardé
    if (!el.dataset.original) {
      el.dataset.original = el.innerText.trim();
    }

    const originalText = el.dataset.original;

    if (originalText.length > 0) {
      const response = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(originalText)}&langpair=${selectedLanguage}&de=emnagarbaa200@gmail.com`);
      const data = await response.json();
      return { el, translatedText: data.responseData.translatedText };
    }

    return null;
  });

  const results = await Promise.all(translationPromises);

  results.forEach(result => {
    if (result) {
      result.el.innerText = result.translatedText;
    }
  });

  button.innerHTML = "Article traduit ✅";
  button.disabled = false;
}

</script>

<style>
/* Style pour le spinner */
.spinner {
  border: 3px solid #f3f3f3; /* Gris clair */
  border-top: 3px solid #3498db; /* Bleu pour la rotation */
  border-radius: 50%;
  width: 15px;
  height: 15px;
  animation: spin 1s linear infinite;
}

/* Animation de rotation */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
