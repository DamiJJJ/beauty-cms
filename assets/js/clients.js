// assets/js/clients.js

/**
 * Beauty CMS - Clients Module JavaScript
 * Pełna obsługa funkcjonalności związanych z klientami
 */

const ClientsModule = {
  // Stan modułu
  state: {
    clients: [],
    searchTerm: "",
    currentClientId: null,
  },

  // Inicjalizacja modułu klientów
  init() {
    console.log("Clients Module - Inicjalizacja...");
    this.bindEvents();
    this.loadClients();
    this.initSearch();
  },

  // Bindowanie event listenerów
  bindEvents() {
    // Formularz klienta
    const clientForm = document.getElementById("clientForm");
    if (clientForm) {
      clientForm.addEventListener("submit", this.handleSubmitClient.bind(this));
    }

    // Przycisk dodawania klienta
    const addClientBtn = document.getElementById("addClientBtn");
    if (addClientBtn) {
      addClientBtn.addEventListener("click", () => {
        this.state.currentClientId = null;
        const form = document.getElementById("clientForm");
        form.reset();
        document.getElementById("clientId").value = "";
        document.getElementById("modalTitle").textContent = "Dodaj nowego klienta";
        BeautyApp.openModal("clientModal");
      });
    }

    // Przycisk usuwania w modalu potwierdzenia
    const deleteConfirmBtn = document.getElementById("deleteConfirmBtn");
    if (deleteConfirmBtn) {
      deleteConfirmBtn.addEventListener("click", this.deleteClient.bind(this));
    }
  },

  // ===== ŁADOWANIE DANYCH =====

  /**
   * Ładuje listę wszystkich klientów
   */
  async loadClients() {
    try {
      const container = document.getElementById("clientsList");
      const loadingOverlay = container.querySelector(".loading-overlay");
      if (loadingOverlay) {
        loadingOverlay.style.display = "flex";
      }

      const response = await BeautyApp.makeRequest("../ajax/clients.php?action=list");

      if (response.success) {
        this.state.clients = response.data || [];
        this.renderClients();
        console.log(`Załadowano ${this.state.clients.length} klientów`);
      } else {
        throw new Error(response.message);
      }
    } catch (error) {
      console.error("Błąd ładowania klientów:", error);
      this.renderError("Nie udało się załadować listy klientów");
    }
  },

  // ===== RENDEROWANIE =====

  /**
   * Renderuje listę klientów
   */
  renderClients() {
    const container = document.getElementById("clientsList");
    if (!container) return;

    const loadingOverlay = container.querySelector(".loading-overlay");
    if (loadingOverlay) {
      loadingOverlay.style.display = "none";
    }

    if (this.state.clients.length === 0) {
      this.renderEmptyState();
      return;
    }

    const html = this.state.clients.map((client) => this.renderClientCard(client)).join("");
    container.innerHTML = html;
  },

  /**
   * Renderuje kartę pojedynczego klienta
   */
  renderClientCard(client) {
    const initials = BeautyApp.getInitials(client.first_name, client.last_name);
    const fullName = `${BeautyApp.escapeHtml(client.first_name)} ${BeautyApp.escapeHtml(client.last_name)}`;
    const phone = BeautyApp.formatPhone(client.phone);
    const appointmentsText = client.appointments_count == 1 ? "wizyta" : "wizyt";
    const createdDate = BeautyApp.formatDate(client.created_at);

    // Dodaj warunek, który wyświetla notatki tylko, jeśli istnieją
    const notesHtml = client.notes
      ? `<div class="client-notes">
            <strong>Notatki:</strong> 
            <span>${BeautyApp.escapeHtml(client.notes)}</span>
           </div>`
      : "";

    return `
        <div class="client-card" data-client-id="${client.id}">
            <div class="client-header">
                <div class="client-avatar">${initials}</div>
                <div class="client-basic-info">
                    <h3 class="client-name">${fullName}</h3>
                    <p class="client-contact">
                        ${phone}
                        ${client.email ? ` • ${BeautyApp.escapeHtml(client.email)}` : ""}
                    </p>
                </div>
            </div>
            
            ${notesHtml}

            <div class="client-stats">
                <span>${client.appointments_count} ${appointmentsText}</span>
                <span>Od ${createdDate}</span>
            </div>
            
            <div class="client-actions">
                <button class="btn btn-outline" onclick="ClientsModule.viewClient(${client.id})" data-tooltip="Zobacz szczegóły">
                    👁️ Zobacz
                </button>
                <button class="btn btn-primary" onclick="ClientsModule.editClient(${client.id})" data-tooltip="Edytuj dane">
                    ✏️ Edytuj
                </button>
                <button class="btn btn-danger" onclick="ClientsModule.openDeleteModal(${client.id}, '${fullName}')" data-tooltip="Usuń klienta">
                    🗑️ Usuń
                </button>
            </div>
        </div>
    `;
  },

  /**
   * Renderuje stan pusty (brak klientów)
   */
  renderEmptyState() {
    const container = document.getElementById("clientsList");
    if (!container) return;

    const message = this.state.searchTerm ? `Nie znaleziono klientów dla frazy: "${this.state.searchTerm}"` : "Brak klientów w bazie danych";
    const actionButton = this.state.searchTerm
      ? `<button class="btn btn-outline" onclick="ClientsModule.clearSearch()">Wyczyść wyszukiwanie</button>`
      : `<button class="btn btn-primary" onclick="BeautyApp.openModal('clientModal')">Dodaj pierwszego klienta</button>`;

    container.innerHTML = `
        <div class="empty-state">
            <div style="font-size: 4rem; margin-bottom: 1rem;">👥</div>
            <p>${message}</p>
            ${actionButton}
        </div>
    `;
  },

  /**
   * Renderuje błąd
   */
  renderError(message) {
    const container = document.getElementById("clientsList");
    if (!container) return;

    const loadingOverlay = container.querySelector(".loading-overlay");
    if (loadingOverlay) {
      loadingOverlay.style.display = "none";
    }

    container.innerHTML = `
        <div class="empty-state">
            <div style="font-size: 4rem; margin-bottom: 1rem;">⚠️</div>
            <p>${message}</p>
            <button class="btn btn-primary" onclick="ClientsModule.loadClients()">Spróbuj ponownie</button>
        </div>
    `;
  },
  // ===== OPERACJE CRUD =====

  /**
   * Obsługuje submit formularza klienta (dodawanie/edycja)
   */
  async handleSubmitClient(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const clientId = formData.get("client_id");

    const data = {};
    for (let [key, value] of formData.entries()) {
      if (key !== "client_id") {
        data[key] = value.trim();
      }
    }

    try {
      let response;
      if (clientId) {
        data.client_id = clientId;
        response = await BeautyApp.makeRequest("../ajax/clients.php", {
          method: "PUT",
          body: JSON.stringify(data),
        });
      } else {
        response = await BeautyApp.makeRequest("../ajax/clients.php", {
          method: "POST",
          body: JSON.stringify(data),
        });
      }

      if (response.success) {
        BeautyApp.showNotification(response.message, "success");
        BeautyApp.closeModal("clientModal");
        this.loadClients(); // Odśwież listę
      } else {
        if (Array.isArray(response.data)) {
          const errors = response.data.join("<br>");
          BeautyApp.showNotification(errors, "error", 8000);
        } else {
          BeautyApp.showNotification(response.message, "error");
        }
      }
    } catch (error) {
      console.error("Błąd zapisywania klienta:", error);
      BeautyApp.showNotification("Nie udało się zapisać klienta", "error");
    }
  },

  /**
   * Pobiera dane klienta i otwiera modal edycji
   */
  async editClient(clientId) {
    try {
      const response = await BeautyApp.makeRequest(`../ajax/clients.php?action=get&id=${clientId}`);
      if (response.success && response.data) {
        const client = response.data;
        this.state.currentClientId = client.id;
        this.fillClientForm(client);
        document.getElementById("modalTitle").textContent = `Edytuj klienta: ${client.first_name} ${client.last_name}`;
        BeautyApp.openModal("clientModal");
      } else {
        BeautyApp.showNotification("Nie znaleziono danych klienta", "error");
      }
    } catch (error) {
      console.error("Błąd pobierania klienta:", error);
      BeautyApp.showNotification("Błąd podczas ładowania danych klienta", "error");
    }
  },

  /**
   * Wypełnia formularz danymi klienta
   */
  fillClientForm(client) {
    document.getElementById("clientId").value = client.id;
    document.getElementById("firstName").value = client.first_name || "";
    document.getElementById("lastName").value = client.last_name || "";
    document.getElementById("phone").value = client.phone || "";
    document.getElementById("email").value = client.email || "";
    document.getElementById("birthDate").value = client.birth_date || "";
    document.getElementById("notes").value = client.notes || "";
  },

  /**
   * Otwiera modal potwierdzenia usunięcia
   */
  openDeleteModal(clientId, clientName) {
    this.state.currentClientId = clientId;
    document.getElementById("clientToDelete").textContent = clientName;
    BeautyApp.openModal("deleteModal");
  },

  /**
   * Usuwa klienta po potwierdzeniu
   */
  async deleteClient() {
    // Sprawdź, czy stan modułu ma ID klienta do usunięcia
    if (!this.state.currentClientId) return;

    try {
      const response = await BeautyApp.makeRequest("../ajax/clients.php", {
        method: "DELETE",
        body: JSON.stringify({ client_id: this.state.currentClientId }),
      });

      if (response.success) {
        BeautyApp.showNotification(response.message, "success");
        BeautyApp.closeModal("deleteModal");
        this.loadClients(); // Odśwież listę
      } else {
        BeautyApp.showNotification(response.message, "error");
      }
    } catch (error) {
      console.error("Błąd usuwania klienta:", error);
      BeautyApp.showNotification("Nie udało się usunąć klienta", "error");
    } finally {
      this.state.currentClientId = null;
    }
  },

  /**
   * Zobacz szczegóły klienta (placeholder)
   */
  viewClient(clientId) {
    window.location.href = `client_detail.php?id=${clientId}`;
  },
};

// ===== INICJALIZACJA =====

document.addEventListener("DOMContentLoaded", () => {
  if (window.location.pathname.includes("clients.php") || document.getElementById("clientsList")) {
    ClientsModule.init();
  }
});

// Eksportuj dla dostępu globalnego
window.ClientsModule = ClientsModule;
