const BeautyApp = {
  makeRequest: async (url, options = {}) => {
    try {
      const response = await fetch(url, options);
      if (!response.ok) {
        throw new Error(`Błąd HTTP: ${response.status}`);
      }
      return await response.json();
    } catch (error) {
      console.error("Błąd zapytania:", error);
      BeautyApp.showNotification("Błąd połączenia z serwerem.", "error");
      throw error;
    }
  },

  // Funkcja do otwierania modali
  openModal: (modalId) => {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.style.display = "block";
    }
  },

  // Funkcja do zamykania modali
  closeModal: (modalId) => {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.style.display = "none";
    }
  },

  // Funkcja do pokazywania notyfikacji
  showNotification: (message, type = "info", duration = 3000) => {
    console.log(`Powiadomienie (${type}): ${message}`);
    // W przyszłości tutaj zaimplementujesz notyfikacje
  },

  // Funkcja pomocnicza do tworzenia inicjałów
  getInitials: (firstName, lastName) => {
    const first = firstName ? firstName[0].toUpperCase() : "";
    const last = lastName ? lastName[0].toUpperCase() : "";
    return first + last;
  },

  // Funkcja do formatowania numeru telefonu
  formatPhone: (phone) => {
    if (!phone) return "Brak";
    const cleaned = ("" + phone).replace(/\D/g, "");
    const match = cleaned.match(/^(\d{3})(\d{3})(\d{3})$/);
    return match ? `${match[1]} ${match[2]} ${match[3]}` : phone;
  },

  // Funkcja do formatowania daty
  formatDate: (dateStr) => {
    if (!dateStr) return "";
    const date = new Date(dateStr);
    return date.toLocaleDateString("pl-PL");
  },
};

BeautyApp.escapeHtml = (text) => {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
};

// Funkcje globalne do użycia w HTML
function openModal(modalId) {
  BeautyApp.openModal(modalId);
}
