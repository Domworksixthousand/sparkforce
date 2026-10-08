<?php
$rv_open = !empty($_SESSION['rv_open']);
$rv_old = [
    'landlord_id' => (string) ($_SESSION['rv_landlord_id'] ?? ''),
    'visit_date' => $_SESSION['rv_visit_date'] ?? '',
    'visit_time' => $_SESSION['rv_visit_time'] ?? '',
    'message' => $_SESSION['rv_message'] ?? ''
];
unset(
    $_SESSION['rv_open'],
    $_SESSION['rv_landlord_id'],
    $_SESSION['rv_visit_date'],
    $_SESSION['rv_visit_time'],
    $_SESSION['rv_message']
);
?>
<dialog id="request_visit_add" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box w-full max-w-lg">
    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3" aria-label="Close">✕</button>
    </form>

    <div class="mb-6">
      <h3 class="text-xl font-bold">Request a Visit</h3>
      <p class="text-sm text-base-content/60">Choose a property and your preferred schedule.</p>
    </div>

    <form id="request_visit_form" action="../functions.php" method="post" class="space-y-4" novalidate>
      <input type="hidden" name="send_request" value="1">

      <label class="form-control w-full">
        <span class="label"><span class="label-text font-medium">Property name</span></span>
        <select name="landlord_id" id="property_list" class="select select-bordered w-full" required>
          <option value="" disabled <?= $rv_old['landlord_id'] === '' ? 'selected' : '' ?>>Select a property</option>
          <?php
            $get_property = $conn->prepare("SELECT `landlord_id`, `property_name` FROM `landlord` WHERE `user_id` != ?");
            $get_property->bind_param("s", $user_id_login);
            $get_property->execute();
            $result_property = $get_property->get_result();
            while ($row_get = $result_property->fetch_assoc()) {
              $id = htmlspecialchars($row_get['landlord_id'], ENT_QUOTES, 'UTF-8');
              $name = htmlspecialchars($row_get['property_name'], ENT_QUOTES, 'UTF-8');
              $selected = $rv_old['landlord_id'] === (string) $row_get['landlord_id'] ? 'selected' : '';
              echo "<option value=\"{$id}\" {$selected}>{$name}</option>";
            }
            $get_property->close();
          ?>
        </select>
        <span class="label hidden" data-error-for="landlord_id">
          <span class="label-text-alt text-error">Please select a property.</span>
        </span>
      </label>

      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <label class="form-control w-full">
          <span class="label"><span class="label-text font-medium">Date</span></span>
          <input type="date" name="visit_date" id="visit_date" class="input input-bordered w-full"
            value="<?= htmlspecialchars($rv_old['visit_date'], ENT_QUOTES, 'UTF-8') ?>" required>
          <span class="label hidden" data-error-for="visit_date">
            <span class="label-text-alt text-error">Pick a valid date.</span>
          </span>
        </label>

        <label class="form-control w-full">
          <span class="label"><span class="label-text font-medium">Time</span></span>
          <input type="time" name="visit_time" id="visit_time" class="input input-bordered w-full"
            value="<?= htmlspecialchars($rv_old['visit_time'], ENT_QUOTES, 'UTF-8') ?>" required>
          <span class="label hidden" data-error-for="visit_time">
            <span class="label-text-alt text-error">Pick a valid time.</span>
          </span>
        </label>
      </div>

      <label class="form-control w-full">
        <span class="label">
          <span class="label-text font-medium">Message <span class="text-base-content/50 font-normal">(optional)</span></span>
          <span class="label-text-alt text-base-content/50"><span id="message_count"><?= mb_strlen($rv_old['message']) ?></span>/250</span>
        </span>
        <textarea name="message" id="visit_message" maxlength="250" rows="3"
          class="textarea textarea-bordered w-full resize-none"
          placeholder="Anything the owner should know?"><?= htmlspecialchars($rv_old['message'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </label>

      <div class="modal-action mt-6">
        <button type="button" class="btn btn-ghost" id="request_visit_cancel">Cancel</button>
        <button type="submit" class="btn btn-primary" id="request_visit_submit">
          <span class="loading loading-spinner loading-sm hidden"></span>
          <span>Send Request</span>
        </button>
      </div>
    </form>
  </div>

  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>

<script>
  (() => {
    const modal = document.getElementById('request_visit_add');
    const form = document.getElementById('request_visit_form');
    const dateInput = document.getElementById('visit_date');
    const timeInput = document.getElementById('visit_time');
    const message = document.getElementById('visit_message');
    const counter = document.getElementById('message_count');
    const submitBtn = document.getElementById('request_visit_submit');
    const spinner = submitBtn.querySelector('.loading');

    const toggleError = (name, show) => {
      const field = form.elements[name];
      const error = form.querySelector(`[data-error-for="${name}"]`);
      field.classList.toggle('input-error', show && field.tagName === 'INPUT');
      field.classList.toggle('select-error', show && field.tagName === 'SELECT');
      error.classList.toggle('hidden', !show);
    };

    const todayString = () => {
      const now = new Date();
      now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
      return now.toISOString().slice(0, 10);
    };

    const isPastTime = () => {
      if (dateInput.value !== todayString() || !timeInput.value) return false;
      const [h, m] = timeInput.value.split(':').map(Number);
      const selected = new Date();
      selected.setHours(h, m, 0, 0);
      return selected < new Date();
    };

    const validators = {
      landlord_id: () => form.elements.landlord_id.value !== '',
      visit_date: () => dateInput.value !== '' && dateInput.value >= todayString(),
      visit_time: () => timeInput.value !== '' && !isPastTime()
    };

    const resetForm = () => {
      dateInput.value = '';
      timeInput.value = '';
      message.value = '';
      form.elements.landlord_id.value = '';
      counter.textContent = '0';
      Object.keys(validators).forEach((name) => toggleError(name, false));
      submitBtn.disabled = false;
      spinner.classList.add('hidden');
    };

    dateInput.min = todayString();

    message.addEventListener('input', () => {
      counter.textContent = message.value.length;
    });

    Object.keys(validators).forEach((name) => {
      form.elements[name].addEventListener('change', () => toggleError(name, !validators[name]()));
    });

    dateInput.addEventListener('change', () => {
      if (timeInput.value) toggleError('visit_time', !validators.visit_time());
    });

    form.addEventListener('submit', (e) => {
      const invalid = Object.keys(validators).filter((name) => !validators[name]());
      Object.keys(validators).forEach((name) => toggleError(name, invalid.includes(name)));

      if (invalid.length) {
        e.preventDefault();
        form.elements[invalid[0]].focus();
        return;
      }

      submitBtn.disabled = true;
      spinner.classList.remove('hidden');
    });

    document.getElementById('request_visit_cancel').addEventListener('click', () => modal.close());
    modal.addEventListener('close', resetForm);

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modal.open) modal.close();
    });

    if (<?= $rv_open ? 'true' : 'false' ?>) modal.show();
  })();
</script>