<!-- Insert Coin / Payment Reminder -->

<div id="payment-banner" style="display: none;" class="payment-modal-overlay">
  <div class="payment-modal-backdrop"></div>

  <div class="payment-modal-card">
    <div class="payment-modal-header">
      <div class="payment-modal-icon">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <div>
        <h2 class="payment-modal-title">Payment Required</h2>
        <p class="payment-modal-subtitle">Please complete your payment to continue</p>
      </div>
    </div>

    <!-- Body -->
    <div class="payment-modal-body">
      <p class="payment-modal-text">
        Your System access is currently on hold. Please settle your pending balance to restore full access to the system.
      </p>

      <!-- Details -->
      <div class="payment-modal-details">
        <span class="payment-modal-label">Due Date</span>
        <span class="payment-modal-value">
          <?= isset($banned_row['banned_at']) ? date('M j, Y', strtotime($banned_row['banned_at'])) : date('M j, Y') ?>
        </span>
      </div>
    </div>
  </div>
</div>

<style>
  .payment-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
  }

  .payment-modal-backdrop {
    position: absolute;
    inset: 0;
    background-color: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(4px);
  }

  .payment-modal-card {
    position: relative;
    width: 100%;
    max-width: 28rem;
    background-color: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  }

  .payment-modal-header {
    background-color: #d97706;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
  }

  .payment-modal-icon {
    flex-shrink: 0;
    width: 2.75rem;
    height: 2.75rem;
    border-radius: 9999px;
    background-color: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .payment-modal-icon svg {
    width: 1.5rem;
    height: 1.5rem;
    color: #ffffff;
  }

  .payment-modal-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
  }

  .payment-modal-subtitle {
    font-size: 0.875rem;
    color: #fef3c7;
    margin: 0.125rem 0 0 0;
  }

  .payment-modal-body {
    padding: 1.25rem 1.5rem;
  }

  .payment-modal-body > * + * {
    margin-top: 1rem;
  }

  .payment-modal-text {
    font-size: 0.875rem;
    color: #475569;
    line-height: 1.625;
    margin: 0;
  }

  .payment-modal-details {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.875rem;
    border-top: 1px solid #f1f5f9;
    padding-top: 1rem;
  }

  .payment-modal-label {
    color: #64748b;
  }

  .payment-modal-value {
    color: #1e293b;
    font-weight: 500;
  }
</style>

<script>
  const dueDate = new Date("2026-11-03");
  const today = new Date();

  if (today >= dueDate) {
    document.getElementById("payment-banner").style.display = "flex";
  }
</script>