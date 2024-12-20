document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");
  const inputs = form.querySelectorAll("input, select, textarea");

  // Create error message element function
  function createErrorMessage(message) {
    const errorDiv = document.createElement("div");
    errorDiv.className = "text-red-500 text-sm mt-10";
    errorDiv.textContent = message;
    return errorDiv;
  }

  // Remove existing error message
  function removeErrorMessage(element) {
    const existingError = element.parentElement.querySelector(".text-red-500");
    if (existingError) {
      existingError.remove();
    }
  }

  // Show error message
  function showError(element, message) {
    removeErrorMessage(element);
    const errorMessage = createErrorMessage(message);
    element.parentElement.appendChild(errorMessage);
    element.classList.add("border-red-500");
  }

  // Remove error styling
  function removeError(element) {
    removeErrorMessage(element);
    element.classList.remove("border-red-500");
  }

  // Validation rules
  const validationRules = {
    nama_sekolah: {
      required: true,
      minLength: 3,
      message: "Nama sekolah harus diisi minimal 3 karakter",
    },
    pangkalan: {
      required: true,
      message: "Pangkalan harus diisi",
    },
    kwaran: {
      required: true,
      message: "Kwaran harus diisi",
    },
    kwarlab: {
      required: true,
      message: "Kwarlab harus diisi",
    },
    pembina: {
      required: true,
      message: "Nama pembina harus diisi",
    },
    alamat_sekolah: {
      required: true,
      minLength: 10,
      message: "Alamat sekolah harus diisi minimal 10 karakter",
    },
    no_gugus: {
      required: true,
      pattern: /^\d+$/,
      message: "Nomor gugus depan harus berupa angka",
    },
    no_telp: {
      required: true,
      pattern: /^(\+62|62|0)8[1-9][0-9]{6,9}$/,
      message: "Nomor telepon tidak valid (format: 08xx...)",
    },
    kategori_perlombaan: {
      required: true,
      message: "Pilih kategori perlombaan",
    },
    metode_pembayaran: {
      required: true,
      message: "Pilih metode pembayaran",
    },
    jenis_pembayaran: {
      required: true,
      message: "Pilih jenis pembayaran",
    },
    regu: {
      required: true,
      message: "Nama regu harus diisi",
    },
  };

  // Validate single field
  function validateField(input) {
    const rules = validationRules[input.name];
    if (!rules) return true;

    const value = input.value.trim();

    if (rules.required && !value) {
      showError(input, rules.message);
      return false;
    }

    if (rules.minLength && value.length < rules.minLength) {
      showError(input, rules.message);
      return false;
    }

    if (rules.pattern && !rules.pattern.test(value)) {
      showError(input, rules.message);
      return false;
    }

    removeError(input);
    return true;
  }

  // Add input event listeners for real-time validation
  inputs.forEach((input) => {
    input.addEventListener("input", () => {
      validateField(input);
    });

    input.addEventListener("blur", () => {
      validateField(input);
    });
  });

  // Form submission handler
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    let isValid = true;

    // Validate all fields
    inputs.forEach((input) => {
      if (!validateField(input)) {
        isValid = false;
      }
    });

    if (isValid) {
      form.submit();
    } else {
      // Scroll to first error
      const firstError = form.querySelector(".text-red-500");
      if (firstError) {
        firstError.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    }
  });

  // Initial validation on page load
  inputs.forEach((input) => {
    if (input.value) {
      validateField(input);
    }
  });
});
