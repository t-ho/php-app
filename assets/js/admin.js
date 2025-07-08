// Admin dashboard entry point
import { DateFormatter } from "./modules/date-formatter.js";
import { TinyMCEEditor } from "./modules/tinymce-editor.js";
import { DashboardCharts } from "./modules/dashboard-charts.js";

document.addEventListener("DOMContentLoaded", async () => {
  const dateFormatter = new DateFormatter();
  dateFormatter.init();

  const editor = new TinyMCEEditor();
  await editor.init();

  // Initialize dashboard charts if we're on the dashboard page
  if (document.getElementById("contentCreationBarChart")) {
    const dashboard = new DashboardCharts();
    await dashboard.init();
  }
});

