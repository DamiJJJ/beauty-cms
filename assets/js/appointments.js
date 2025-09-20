const AppointmentsModule = {
  state: {
    appointments: [],
    currentAppointmentId: null,
  },

  init() {
    this.bindEvents();
    this.loadAppointments();
  },

  bindEvents() {
    const appointmentForm = document.getElementById("appointmentForm");
    if (appointmentForm) {
      appointmentForm.addEventListener("submit", this.handleSubmitAppointment.bind(this));
    }

    const addAppointmentBtn = document.getElementById("addAppointmentBtn");
    if (addAppointmentBtn) {
      addAppointmentBtn.addEventListener("click", () => {
        this.state.currentAppointmentId = null;
        const form = document.getElementById("appointmentForm");
        form.reset();
        document.getElementById("appointmentId").value = "";
        document.getElementById("modalTitle").textContent = "Add New Appointment";
        BeautyApp.openModal("appointmentModal");
      });
    }

    const deleteConfirmBtn = document.getElementById("deleteConfirmBtn");
    if (deleteConfirmBtn) {
      deleteConfirmBtn.addEventListener("click", this.deleteAppointment.bind(this));
    }
  },

  async loadAppointments() {
    try {
      const container = document.getElementById("appointmentsList");
      const loadingOverlay = container.querySelector(".loading-overlay");
      if (loadingOverlay) {
        loadingOverlay.style.display = "flex";
      }
      const response = await BeautyApp.makeRequest("../ajax/appointments.php?action=list");
      if (response.success) {
        this.state.appointments = response.data || [];
        this.renderAppointments();
      } else {
        this.renderError("Failed to load appointments.");
      }
    } catch (error) {
      this.renderError("Server error. Please try again.");
    }
  },

  renderAppointments() {
    const container = document.getElementById("appointmentsList");
    if (!container) return;
    const loadingOverlay = container.querySelector(".loading-overlay");
    if (loadingOverlay) {
      loadingOverlay.style.display = "none";
    }
    if (this.state.appointments.length === 0) {
      container.innerHTML = `<div class="empty-state">No appointments scheduled.</div>`;
      return;
    }
    let html = "";
    this.state.appointments.forEach((appointment) => {
      html += this.renderAppointmentCard(appointment);
    });
    container.innerHTML = `<div class="appointments-grid">${html}</div>`;
  },

  renderAppointmentCard(appointment) {
    const clientName = `${BeautyApp.escapeHtml(appointment.first_name)} ${BeautyApp.escapeHtml(appointment.last_name)}`;
    const date = new Date(appointment.appointment_date);
    const formattedDate = date.toLocaleDateString("en-US");
    const formattedTime = date.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit" });

    return `
            <div class="appointment-card">
                <h3>Appointment with: ${clientName}</h3>
                <p><strong>Treatment Type:</strong> ${BeautyApp.escapeHtml(appointment.treatment_type)}</p>
                <p><strong>Date:</strong> ${formattedDate}</p>
                <p><strong>Time:</strong> ${formattedTime}</p>
                <p><strong>Price:</strong> ${BeautyApp.escapeHtml(appointment.price)} USD</p>
                <div class="appointment-actions">
                    <button class="btn btn-primary" onclick="AppointmentsModule.editAppointment(${appointment.id})">Edit</button>
                    <button class="btn btn-danger" onclick="AppointmentsModule.openDeleteModal(${appointment.id})">Delete</button>
                </div>
            </div>
        `;
  },

  renderError(message) {
    const container = document.getElementById("appointmentsList");
    if (!container) return;
    const loadingOverlay = container.querySelector(".loading-overlay");
    if (loadingOverlay) {
      loadingOverlay.style.display = "none";
    }
    container.innerHTML = `<div class="empty-state">${message}</div>`;
  },

  async handleSubmitAppointment(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    const appointmentId = data["id"];

    try {
      let response;
      if (appointmentId) {
        response = await BeautyApp.makeRequest("../ajax/appointments.php", {
          method: "PUT",
          body: JSON.stringify(data),
        });
      } else {
        response = await BeautyApp.makeRequest("../ajax/appointments.php", {
          method: "POST",
          body: JSON.stringify(data),
        });
      }
      if (response.success) {
        BeautyApp.showNotification(response.message, "success");
        BeautyApp.closeModal("appointmentModal");
        this.loadAppointments();
      } else {
        BeautyApp.showNotification(response.message, "error");
      }
    } catch (error) {
      BeautyApp.showNotification("Server error. Failed to save appointment.", "error");
    }
  },

  async editAppointment(id) {
    this.state.currentAppointmentId = id;
    try {
      const response = await BeautyApp.makeRequest(`../ajax/appointments.php?action=get_appointment&id=${id}`);
      if (response.success && response.data) {
        const appointment = response.data;
        const form = document.getElementById("appointmentForm");
        document.getElementById("appointmentId").value = appointment.id;
        document.getElementById("clientSelect").value = appointment.client_id;
        document.getElementById("treatmentType").value = appointment.treatment_type;
        const localDate = new Date(appointment.appointment_date);
        localDate.setMinutes(localDate.getMinutes() - localDate.getTimezoneOffset());
        document.getElementById("appointmentDate").value = localDate.toISOString().slice(0, 16);
        document.getElementById("price").value = appointment.price;
        document.getElementById("appointmentNotes").value = appointment.notes || "";

        document.getElementById("modalTitle").textContent = "Edit Appointment";
        BeautyApp.openModal("appointmentModal");
      } else {
        BeautyApp.showNotification("Appointment not found.", "error");
      }
    } catch (error) {
      BeautyApp.showNotification("Error loading appointment data.", "error");
    }
  },

  openDeleteModal(id) {
    this.state.currentAppointmentId = id;
    BeautyApp.openModal("deleteModal");
  },

  async deleteAppointment() {
    if (!this.state.currentAppointmentId) return;

    try {
      const response = await BeautyApp.makeRequest("../ajax/appointments.php", {
        method: "DELETE",
        body: JSON.stringify({ id: this.state.currentAppointmentId }),
      });
      if (response.success) {
        BeautyApp.showNotification(response.message, "success");
        BeautyApp.closeModal("deleteModal");
        this.loadAppointments();
      } else {
        BeautyApp.showNotification(response.message, "error");
      }
    } catch (error) {
      BeautyApp.showNotification("Error deleting appointment.", "error");
    }
  },
};

document.addEventListener("DOMContentLoaded", () => {
  if (window.location.pathname.includes("appointments.php") || document.getElementById("appointmentsList")) {
    AppointmentsModule.init();
  }
});
