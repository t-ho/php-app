export class ReportGenerator {
  constructor(dashboardCharts) {
    this.dashboardCharts = dashboardCharts;
  }

  init() {
    const generateReportBtn = document.getElementById("generateReportBtn");
    if (generateReportBtn) {
      generateReportBtn.addEventListener("click", () => this.generateReport());
    }
  }

  async generateReport() {
    const generateReportBtn = document.getElementById("generateReportBtn");

    try {
      generateReportBtn.disabled = true;
      generateReportBtn.innerHTML =
        '<i class="fas fa-spinner fa-spin me-1"></i>Generating...';

      const chartImages = await this.dashboardCharts.captureChartsAsImages();

      // Get CSRF token
      const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
      if (!csrfToken) {
        throw new Error("CSRF token not found");
      }

      // Create form data
      const formData = new FormData();
      formData.append("csrf_token", csrfToken);
      formData.append("chartData", JSON.stringify(chartImages));

      // Make request
      const response = await fetch("/admin/generate-report", {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      // Get the PDF blob
      const blob = await response.blob();

      // Create download link
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      const now = new Date();
      const timestamp = now
        .toLocaleString("sv-SE")
        .replace(" ", "_")
        .replace(/:/g, "-");
      a.download = `dashboard-report_${timestamp}.pdf`;
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      window.URL.revokeObjectURL(url);
    } catch (error) {
      console.error("Error generating report:", error);
      alert("Failed to generate report. Please try again.");
    } finally {
      generateReportBtn.disabled = false;
      generateReportBtn.innerHTML =
        '<i class="fas fa-file-pdf me-1"></i>Generate Report';
    }
  }
}
