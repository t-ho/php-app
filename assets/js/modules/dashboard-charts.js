export class DashboardCharts {
  constructor() {
    this.charts = {};
    this.initialized = false;
  }

  async loadChartJS() {
    if (typeof window.Chart !== "undefined") {
      return window.Chart;
    }

    return new Promise((resolve, reject) => {
      const script = document.createElement("script");
      script.src = "https://cdn.jsdelivr.net/npm/chart.js";
      script.onload = () => {
        if (typeof window.Chart !== "undefined") {
          resolve(window.Chart);
        } else {
          reject(new Error("Chart.js failed to load"));
        }
      };
      script.onerror = () => reject(new Error("Failed to load Chart.js"));
      document.head.appendChild(script);
    });
  }

  async init() {
    if (this.initialized) return;

    try {
      // Load Chart.js
      await this.loadChartJS();

      this.createContentCreationBarChart();
      this.createContentDistributionPieChart();
      this.createUserRoleDistributionPieChart();
      this.createPopularPostsBarChart();

      this.initialized = true;
    } catch (error) {
      console.error("Failed to initialize dashboard charts:", error);
    }
  }

  createContentCreationBarChart() {
    const ctx = document.getElementById("contentCreationBarChart");
    if (!ctx) return;

    const totalPosts = parseInt(ctx.dataset.totalPosts);
    const totalComments = parseInt(ctx.dataset.totalComments);

    this.charts.contentCreation = new Chart(ctx.getContext("2d"), {
      type: "bar",
      data: {
        labels: ["Posts", "Comments"],
        datasets: [
          {
            label: "Content Created",
            data: [totalPosts, totalComments],
            backgroundColor: [
              "rgba(54, 162, 235, 0.8)",
              "rgba(75, 192, 192, 0.8)",
            ],
            borderColor: ["rgba(54, 162, 235, 1)", "rgba(75, 192, 192, 1)"],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
            },
          },
        },
      },
    });
  }

  createContentDistributionPieChart() {
    const ctx = document.getElementById("contentDistributionPieChart");
    if (!ctx) return;

    const totalPosts = parseInt(ctx.dataset.totalPosts);
    const totalComments = parseInt(ctx.dataset.totalComments);

    this.charts.contentDistribution = new Chart(ctx.getContext("2d"), {
      type: "doughnut",
      data: {
        labels: ["Posts", "Comments"],
        datasets: [
          {
            data: [totalPosts, totalComments],
            backgroundColor: [
              "rgba(255, 99, 132, 0.8)",
              "rgba(54, 162, 235, 0.8)",
            ],
            borderColor: ["rgba(255, 99, 132, 1)", "rgba(54, 162, 235, 1)"],
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
          },
        },
      },
    });
  }

  createUserRoleDistributionPieChart() {
    const ctx = document.getElementById("userRoleDistributionPieChart");
    if (!ctx) return;

    const roleData = JSON.parse(ctx.dataset.roleDistribution);

    this.charts.userRoleDistribution = new Chart(ctx.getContext("2d"), {
      type: "doughnut",
      data: {
        labels: roleData.map(
          (role) => role.role.charAt(0).toUpperCase() + role.role.slice(1),
        ),
        datasets: [
          {
            data: roleData.map((role) => role.count),
            backgroundColor: [
              "rgba(255, 193, 7, 0.8)",
              "rgba(220, 53, 69, 0.8)",
              "rgba(13, 202, 240, 0.8)",
              "rgba(25, 135, 84, 0.8)",
              "rgba(102, 16, 242, 0.8)",
            ],
            borderColor: [
              "rgba(255, 193, 7, 1)",
              "rgba(220, 53, 69, 1)",
              "rgba(13, 202, 240, 1)",
              "rgba(25, 135, 84, 1)",
              "rgba(102, 16, 242, 1)",
            ],
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
          },
        },
      },
    });
  }

  createPopularPostsBarChart() {
    const ctx = document.getElementById("popularPostsBarChart");
    if (!ctx) return;

    const posts = JSON.parse(ctx.dataset.topViewedPosts);

    this.charts.popularPosts = new Chart(ctx.getContext("2d"), {
      type: "bar",
      data: {
        labels: posts.map((post) =>
          post.title.length > 35
            ? post.title.substring(0, 35) + "..."
            : post.title,
        ),
        datasets: [
          {
            label: "Views",
            data: posts.map((post) => post.views),
            backgroundColor: "rgba(153, 102, 255, 0.8)",
            borderColor: "rgba(153, 102, 255, 1)",
            borderWidth: 1,
          },
        ],
      },
      options: {
        indexAxis: "y",
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
        },
        scales: {
          x: {
            beginAtZero: true,
          },
        },
      },
    });
  }

  // Method to capture charts as images for PDF report
  async captureChartsAsImages() {
    const chartImages = {};

    // Capture content creation bar chart
    if (this.charts.contentCreation) {
      chartImages.contentCreation = this.charts.contentCreation.toBase64Image();
    }

    // Capture content distribution pie chart
    if (this.charts.contentDistribution) {
      chartImages.contentDistribution =
        this.charts.contentDistribution.toBase64Image();
    }

    // Capture user role distribution chart
    if (this.charts.userRoleDistribution) {
      chartImages.userRoleDistribution =
        this.charts.userRoleDistribution.toBase64Image();
    }

    // Capture popular posts chart (horizontal)
    if (this.charts.popularPosts) {
      chartImages.topViewedPosts = this.charts.popularPosts.toBase64Image();
    }

    return chartImages;
  }

  // Method to destroy all charts
  destroy() {
    Object.values(this.charts).forEach((chart) => {
      if (chart) chart.destroy();
    });
    this.charts = {};
    this.initialized = false;
  }
}
