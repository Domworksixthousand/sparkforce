<?php
include 'my_property.php';

if (isset($_GET['property_id'])) {
    $landlord_id = $_GET['property_id'] ?? '';
} else {
    header("Location: index.php");
    exit;
}
?>

<script src="https://cdn.tiny.cloud/1/ssew95wvyspsqckuynqjy38ov5ktrks0qbp94mxchgh1ucty/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Professional Modal Wrapper & Backdrop -->
<div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 overflow-y-auto animate-fade-in">

  <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all">

    <!-- Modal Header -->
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70 sticky top-0 z-10 backdrop-blur-md">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-semibold">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M8 2v4"/>
            <path d="M16 2v4"/>
            <rect width="18" height="18" x="3" y="4" rx="2"/>
            <path d="M3 10h18"/>
            <path d="m9 16 2 2 4-4"/>
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-bold text-slate-800">Transient House Configuration</h2>
          <p class="text-xs text-slate-500">Manage transient house details, pricing, photos, unit specs, and amenities.</p>
        </div>
      </div>

      <a href="my_property.php?property_id=<?php echo urlencode($landlord_id); ?>"
         class="w-8 h-8 rounded-full bg-slate-100 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors">
        ✕
      </a>
    </div>



    <!-- Modal Form Body -->
    <form action="../functions.php" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto space-y-8" id="mainForm">
        <input type="hidden" name="landlord_id" value="<?php echo htmlspecialchars($landlord_id); ?>">

        <!-- ============================================ -->
        <!-- SECTION 1: TRANSIENT HOUSE INFORMATION -->
        <!-- ============================================ -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Transient House Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Transient Name / Number *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                            </svg>
                        </span>
                        <input type="text"
                               class="autoInput w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                               name="transient_name"
                               value="<?php echo htmlspecialchars($_SESSION['transient_name'] ?? ''); ?>"
                               placeholder="Enter Name / Number"
                               required />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Price *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none font-semibold text-sm">₱</span>
                        <input type="text"
                               class="numbers_only w-full pl-8 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                               name="transient_price"
                               value="<?php echo htmlspecialchars($_SESSION['transient_price'] ?? ''); ?>"
                               placeholder="Enter Price / Hour"
                               required />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Rate (Per Month/Night/Week/Hour) *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                        </span>
                        <input type="text"
                               class="autoInput w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                               name="transient_rate"
                               value="<?php echo htmlspecialchars($_SESSION['transient_rate'] ?? ''); ?>"
                               placeholder="Enter Rate"
                               required />
                    </div>
                </div>
            </div>

            <!-- Cover Photo -->
            <div class="space-y-1.5 pt-2" id="cover-section">
                <label class="block text-xs font-semibold text-slate-600">Cover Photo *</label>

                <?php if (!empty($_SESSION['transient_cover'])): ?>
                    <input type="hidden" name="old_cover" value="<?php echo htmlspecialchars($_SESSION['transient_cover']); ?>">
                    <div class="flex items-center justify-between bg-emerald-50/60 border border-emerald-100 p-2.5 rounded-xl text-xs mb-2">
                        <div class="flex items-center gap-3">
                            <img src="../assets/uploads/<?php echo htmlspecialchars($_SESSION['transient_cover']); ?>"
                                 class="w-10 h-10 object-cover rounded-lg border border-white shadow-xs"
                                 alt="Preview">
                            <span class="text-emerald-900">Previously selected:
                                <strong class="underline font-medium"><?php echo htmlspecialchars($_SESSION['transient_cover']); ?></strong>
                            </span>
                        </div>
                        <span class="px-2 py-1 bg-emerald-600 text-white rounded-md text-[10px] font-bold uppercase tracking-wider">Retained</span>
                    </div>
                <?php endif; ?>

                <label id="cover-label" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-200 border-dashed rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-50 hover:border-emerald-400 transition-all">
                    <div class="flex flex-col items-center justify-center pt-4 pb-4" id="cover-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 mb-1">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <p class="text-xs text-slate-500"><span class="font-semibold text-emerald-600">Click to upload</span> or drag and drop</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">JPEG, PNG, JPG accepted</p>
                    </div>
                    <input type="file"
                           class="hidden"
                           id="cover"
                           name="transient_cover"
                           accept="image/jpeg, image/png, image/jpg"
                           <?php echo !empty($_SESSION['transient_cover']) ? '' : 'required'; ?> />
                </label>

                <div id="cover-preview-container" class="hidden mt-3 flex items-center justify-between bg-emerald-50/60 border border-emerald-100 p-2.5 rounded-xl text-xs">
                    <div class="flex items-center gap-3">
                        <img id="cover-preview-img" src="" class="w-12 h-12 object-cover rounded-lg border border-white shadow-sm" alt="Cover Preview">
                        <div>
                            <p class="text-emerald-900 font-semibold">Selected file:</p>
                            <p id="cover-preview-name" class="text-emerald-700 underline truncate max-w-[200px]"></p>
                            <p id="cover-preview-size" class="text-slate-400 text-[10px] mt-0.5"></p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-emerald-600 text-white rounded-md text-[10px] font-bold uppercase tracking-wider shrink-0">New</span>
                </div>
            </div>

            <!-- Other Information -->
            <div class="space-y-1.5 pt-2">
                <label class="block text-xs font-semibold text-slate-600">Other Information *</label>
                <div class="border border-slate-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-emerald-500/20 focus-within:border-emerald-500 transition-all">
                    <textarea
                        id="myEditor"
                        name="transient_other_info"><?php echo htmlspecialchars($_SESSION['transient_other_info'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECTION 2: UNITS SPECIFICATION -->
        <!-- ============================================ -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Units Specification</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Unit Type *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                        </span>
                        <select class="w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" name="transient_type" required>
                            <option value="" disabled <?php echo empty($_SESSION['type']) ? 'selected' : ''; ?>>Select Unit Type</option>
                            <option value="Studio Unit" <?php echo (($_SESSION['type'] ?? '') == 'Studio Unit') ? 'selected' : ''; ?>>Studio Unit</option>
                            <option value="1-Bedroom Transient Unit" <?php echo (($_SESSION['type'] ?? '') == '1-Bedroom Transient Unit') ? 'selected' : ''; ?>>1-Bedroom Transient Unit</option>
                            <option value="2-Bedroom Transient Unit" <?php echo (($_SESSION['type'] ?? '') == '2-Bedroom Transient Unit') ? 'selected' : ''; ?>>2-Bedroom Transient Unit</option>
                            <option value="Entire House / Unit" <?php echo (($_SESSION['type'] ?? '') == 'Entire House / Unit') ? 'selected' : ''; ?>>Entire House / Unit</option>
                            <option value="Private Room" <?php echo (($_SESSION['type'] ?? '') == 'Private Room') ? 'selected' : ''; ?>>Private Room</option>
                            <option value="Shared Room" <?php echo (($_SESSION['type'] ?? '') == 'Shared Room') ? 'selected' : ''; ?>>Shared Room</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Square Area *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 8 6-3-6-3v10"/><path d="m8 11.99-5.5 3.14a1 1 0 0 0 0 1.74l8.5 4.86a2 2 0 0 0 2 0l8.5-4.86a1 1 0 0 0 0-1.74L16 12"/><path d="m6.49 12.85 11.02 6.3"/><path d="M17.51 12.85 6.5 19.15"/></svg>
                        </span>
                        <input type="text"
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                               name="transient_square_area"
                               value="<?php echo htmlspecialchars($_SESSION['square_area'] ?? ''); ?>"
                               placeholder="Enter Unit Square Area"
                               required />
                    </div>
                </div>
            </div>

            <!-- Multiple Photos -->
            <div class="space-y-1.5 pt-2" id="gallery-section">
                <label class="block text-xs font-semibold text-slate-600">Multiple Photos (Upload 3 to 10 Photos) *</label>

                <?php if (!empty($_SESSION['gallery'])): ?>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2 mb-2">
                        <?php foreach ($_SESSION['gallery'] as $img): ?>
                            <div class="relative">
                                <img src="../assets/uploads/<?php echo htmlspecialchars($img); ?>"
                                     class="w-full h-16 object-cover rounded-lg border border-emerald-100">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="flex items-center gap-2 bg-emerald-50/60 border border-emerald-100 p-2.5 rounded-xl text-xs mb-2">
                        <span class="px-2 py-1 bg-emerald-600 text-white rounded-md text-[10px] font-bold uppercase tracking-wider shrink-0">Retained</span>
                        <span class="text-emerald-900"><?php echo count($_SESSION['gallery']); ?> photo(s) retained. Upload new photos below to replace them.</span>
                    </div>
                <?php endif; ?>

                <label id="gallery-label" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-200 border-dashed rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-50 hover:border-emerald-400 transition-all">
                    <div class="flex flex-col items-center justify-center pt-4 pb-4" id="gallery-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 mb-1">
                            <path d="m22 11-1.296-1.296a2.4 2.4 0 0 0-3.408 0L11 16"/><path d="M4 8a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2"/><circle cx="13" cy="7" r="1" fill="currentColor"/><rect x="8" y="2" width="14" height="14" rx="2"/>
                        </svg>
                        <p class="text-xs text-slate-500"><span class="font-semibold text-emerald-600">Click to upload</span> or drag and drop</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Select 3–10 photos (JPEG, PNG, JPG)</p>
                    </div>
                    <input type="file"
                           class="hidden"
                           id="gallery"
                           name="gallery[]"
                           accept="image/jpeg, image/png, image/jpg"
                           multiple
                           <?php echo !empty($_SESSION['gallery']) ? '' : 'required'; ?> />
                </label>

                <div id="gallery-preview-container" class="hidden mt-3 grid grid-cols-4 sm:grid-cols-5 gap-2"></div>
                <p class="text-[10px] text-slate-400 mt-1">Please select 3–10 photos.</p>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECTION 3: ROOM AMENITIES -->
        <!-- ============================================ -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Room Amenities</h3>
                <button type="button" id="addamenBtn3"
                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Amenities
                </button>
            </div>

            <div id="amenities-container3" class="space-y-2">
                <?php
                $active = "yes";
                $get_amen = $conn->prepare("SELECT * FROM amenities WHERE user_id=? AND active=?");
                $get_amen->bind_param("ss", $user_id_login, $active);
                $get_amen->execute();
                $result = $get_amen->get_result();

                $amenities = [];
                while ($row = $result->fetch_assoc()) {
                    $amenities[] = $row;
                }

                if (empty($_SESSION['amenities'])) {
                    $_SESSION['amenities'] = [""];
                }

                $index = 0;
                foreach ($_SESSION['amenities'] as $selectedAmen) {
                ?>
                <div class="amen-item flex items-center gap-2 bg-slate-50/50 border border-slate-200 rounded-xl p-2">
                    <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            name="transient_amenity[]" required>
                        <option value="" disabled <?php echo empty($selectedAmen) ? 'selected' : ''; ?>>Select Amenity</option>
                        <?php foreach ($amenities as $amen) { ?>
                            <option value="<?php echo htmlspecialchars($amen['amen_id']); ?>"
                                <?php echo ($selectedAmen == $amen['amen_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($amen['amenity']); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <?php if ($index > 0) { ?>
                    <button type="button" class="remove-amen-btn p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs transition-colors shrink-0" title="Remove">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                    <?php } ?>
                </div>
                <?php $index++; } ?>
            </div>
        </div>

        <!-- Submit -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 sticky bottom-0 bg-white py-3 -mx-6 px-6">
            <a href="my_property.php?property_id=<?php echo urlencode($landlord_id); ?>"
               class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-colors">
               Cancel
            </a>
            <button type="submit" name="save_transient"
                    class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save
            </button>
        </div>
    </form>
  </div>
</div>

<script>
tinymce.init({
    selector: '#myEditor',
    height: 500,
    menubar: 'file edit view insert format tools table help',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'visualchars', 'code',
        'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount',
        'emoticons', 'template', 'codesample', 'directionality', 'nonbreaking',
        'pagebreak', 'quickbars', 'save'
    ],
    toolbar1: 'undo redo | save | newdocument | bold italic underline strikethrough | ' +
              'forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
              'bullist numlist | outdent indent',
    toolbar2: 'formatselect fontselect fontsizeselect | ' +
              'link image media | table | codesample code | ' +
              'charmap emoticons | insertdatetime | ' +
              'searchreplace | visualblocks visualchars | ' +
              'ltr rtl | pagebreak nonbreaking | ' +
              'fullscreen preview | removeformat | help',

    font_formats:
        'Arial=arial,helvetica,sans-serif;' +
        'Arial Black=arial black,avant garde;' +
        'Courier New=courier new,courier;' +
        'Georgia=georgia,palatino;' +
        'Helvetica=helvetica;' +
        'Impact=impact,chicago;' +
        'Tahoma=tahoma,arial,helvetica,sans-serif;' +
        'Times New Roman=times new roman,times;' +
        'Trebuchet MS=trebuchet ms,geneva;' +
        'Verdana=verdana,geneva;',

    fontsize_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt 72pt',
    block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Blockquote=blockquote; Code=pre',

    image_advtab: true,
    image_caption: true,
    image_title: true,
    automatic_uploads: true,
    file_picker_types: 'image media',
    images_upload_url: '../upload_image.php',
    images_upload_handler: function (blobInfo, success, failure) {
        var formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        fetch('../upload_image.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(result => result.location ? success(result.location) : failure('Upload failed: ' + result.error))
            .catch(error => failure('Upload error: ' + error));
    },

    table_default_attributes: { border: '1' },
    table_default_styles: { 'border-collapse': 'collapse', 'width': '100%' },
    table_responsive_width: true,
    table_column_resizing: 'resizetable',
    media_live_embeds: true,
    link_default_target: '_blank',
    link_assume_external_targets: true,
    link_context_toolbar: true,

    codesample_languages: [
        { text: 'HTML/XML', value: 'markup' },
        { text: 'JavaScript', value: 'javascript' },
        { text: 'CSS', value: 'css' },
        { text: 'PHP', value: 'php' },
        { text: 'Python', value: 'python' },
        { text: 'SQL', value: 'sql' },
    ],

    templates: [
        { title: 'Property Description', description: 'Template for property description', content: '<h2>Property Overview</h2><p>Enter property details here...</p><h3>Amenities</h3><ul><li>Amenity 1</li><li>Amenity 2</li></ul>' },
        { title: 'House Rules', description: 'Template for house rules', content: '<h2>House Rules</h2><ol><li>Rule 1</li><li>Rule 2</li><li>Rule 3</li></ol>' },
        { title: 'Room Details', description: 'Template for room details', content: '<h2>Room Details</h2><table border="1" style="width:100%;border-collapse:collapse;"><tr><th>Feature</th><th>Details</th></tr><tr><td>Size</td><td>Enter size</td></tr></table>' }
    ],

    quickbars_insert_toolbar: 'quickimage quicktable | hr pagebreak',
    quickbars_selection_toolbar: 'bold italic underline | formatselect | link blockquote',

    content_style: `
        body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #333; padding: 10px; }
        h1, h2, h3, h4, h5, h6 { color: #1a1a2e; margin-top: 1em; }
        table { border-collapse: collapse; width: 100%; }
        table td, table th { border: 1px solid #ddd; padding: 8px; }
        table th { background-color: #f2f2f2; font-weight: bold; }
        img { max-width: 100%; height: auto; }
        pre { background: #f4f4f4; border: 1px solid #ddd; padding: 10px; border-radius: 4px; }
        blockquote { border-left: 4px solid #10b981; margin: 0; padding-left: 16px; color: #555; }
    `,

    paste_data_images: true,
    paste_as_text: false,
    smart_paste: true,
    browser_spellcheck: true,
    wordcount_countcharacters: true,
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    autosave_restore_when_empty: true,
    autosave_retention: '2m',
    nonbreaking_force_tab: true,
    resize: 'both',
    statusbar: true,
    elementpath: true,

    setup: function (editor) {
        editor.on('change keyup', function () {
            editor.save();
        });
    },

    init_instance_callback: function (editor) {
        const content = <?php echo json_encode($_SESSION['transient_other_info'] ?? ''); ?>;
        if (content) {
            editor.setContent(content);
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('mainForm');
    if (form) {
        form.addEventListener('submit', function () {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
        });
    }

    // ============================================
    // COVER PHOTO LIVE PREVIEW
    // ============================================
    const coverInput = document.getElementById('cover');
    const coverPreviewContainer = document.getElementById('cover-preview-container');
    const coverPreviewImg = document.getElementById('cover-preview-img');
    const coverPreviewName = document.getElementById('cover-preview-name');
    const coverPreviewSize = document.getElementById('cover-preview-size');
    const coverLabel = document.getElementById('cover-label');

    if (coverInput) {
        coverInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (ev) {
                coverPreviewImg.src = ev.target.result;
                coverPreviewName.textContent = file.name;
                coverPreviewSize.textContent = (file.size / 1024).toFixed(1) + ' KB';

                coverPreviewContainer.classList.remove('hidden');
                coverLabel.classList.add('border-emerald-400', 'bg-emerald-50/30');

                document.getElementById('cover-placeholder').innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500 mb-1">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <p class="text-xs text-emerald-600 font-semibold">Photo selected!</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Click to change</p>
                `;
            };
            reader.readAsDataURL(file);
        });
    }

    // ============================================
    // GALLERY PHOTOS LIVE PREVIEW
    // ============================================
    const galleryInput = document.getElementById('gallery');
    const galleryPreviewContainer = document.getElementById('gallery-preview-container');
    const galleryLabel = document.getElementById('gallery-label');

    if (galleryInput) {
        galleryInput.addEventListener('change', function (e) {
            const files = Array.from(e.target.files);
            if (!files.length) return;

            galleryPreviewContainer.innerHTML = '';
            galleryPreviewContainer.classList.remove('hidden');
            galleryLabel.classList.add('border-emerald-400', 'bg-emerald-50/30');

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    const wrap = document.createElement('div');
                    wrap.className = 'relative';
                    wrap.innerHTML = `<img src="${ev.target.result}" class="w-full h-16 object-cover rounded-lg border border-emerald-200">`;
                    galleryPreviewContainer.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            });

            document.getElementById('gallery-placeholder').innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500 mb-1">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <p class="text-xs text-emerald-600 font-semibold">${files.length} photo(s) selected!</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Click to change</p>
            `;
        });
    }


});
</script>