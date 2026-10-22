(function () {
    const POLL_INTERVAL_MS = 30000;

    async function fetchStats() {
        try {
            const res = await fetch("/api/dashboard/stats");
            if (!res.ok) throw new Error("Request failed: " + res.status);
            const data = await res.json();

            const totalAccountsEl = document.getElementById("statTotalAccounts");
            const totalMoneyEl = document.getElementById("statTotalMoney");
            const totalTransactionsEl = document.getElementById("statTotalTransactions");

            if (totalAccountsEl) totalAccountsEl.textContent = data.total_accounts;
            if (totalMoneyEl) totalMoneyEl.textContent = "$" + Number(data.total_money_in_bank).toFixed(2);
            if (totalTransactionsEl) totalTransactionsEl.textContent = data.total_transactions_today;
        } catch (err) {
            console.error("Dashboard stats polling error:", err);
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        fetchStats();
        setInterval(fetchStats, POLL_INTERVAL_MS);
    });
})();
