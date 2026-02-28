# 🧹 PROJECT MAINTENANCE GUIDE

Panduan cepat untuk mempertahankan kualitas kode project setelah cleanup.

---

## 🔍 QUICK REFERENCE

### Struktur Akhir

```
src/pages/
├── pengurus/
│   ├── Dashboard.jsx          (placeholder untuk dashboard pengurus)
│   ├── Login.jsx              (login page untuk pengurus)
│   └── ProfilOrganisasi.jsx   (profil organisasi)
│
└── kemahasiswaan/
    └── DashboardPage.jsx      (dashboard kemahasiswaan dengan stats & charts)
```

### Dokumen Penting

1. **CLEANUP_REPORT.md** - Penjelasan lengkap apa & kenapa
2. **IMPORT_GUIDELINES.md** - Contoh import yang benar
3. **FINAL_STRUCTURE.md** - Summary perubahan

---

## ⚡ COMMAND CEPAT

```bash
# Format semua file
npm run format

# Check linting
npm run lint

# Fix linting otomatis
npm run lint:fix

# Verify formatting
npm run format:check
```

---

## 🚫 YANG TIDAK BOLEH DILAKUKAN

```javascript
❌ SALAH - TypeScript syntax di .jsx file
const getData = (status: string): void => {

✅ BENAR - Pure JavaScript
const getData = (status) => {

---

❌ SALAH - Double quotes
import { Component } from "react";

✅ BENAR - Single quotes
import { Component } from 'react';

---

❌ SALAH - 4-space indentation
export default function Dashboard() {
    return (
        <div>

✅ BENAR - 2-space indentation
export default function Dashboard() {
  return (
    <div>

---

❌ SALAH - File dengan timestamp
Dashboard_20251223120022.jsx
Login_20251223114513.jsx

✅ BENAR - File clean tanpa timestamp
Dashboard.jsx
Login.jsx
```

---

## 📝 CHECKLIST SEBELUM GIT COMMIT

- [ ] Run `npm run format`
- [ ] Run `npm run lint:fix`
- [ ] No error di console
- [ ] Files match guidelines di IMPORT_GUIDELINES.md
- [ ] No new files dengan timestamp
- [ ] Commit message jelas dan deskriptif

---

## 🔗 LINKS PENTING

| Dokumen                                        | Fungsi                        |
| ---------------------------------------------- | ----------------------------- |
| [CLEANUP_REPORT.md](./CLEANUP_REPORT.md)       | Penjelasan problem & solution |
| [IMPORT_GUIDELINES.md](./IMPORT_GUIDELINES.md) | Contoh import benar           |
| [FINAL_STRUCTURE.md](./FINAL_STRUCTURE.md)     | Summary perubahan             |
| [.prettierrc](./.prettierrc)                   | Prettier config               |
| [.eslintrc.json](./.eslintrc.json)             | ESLint config                 |

---

## 🎯 TIPS HARIAN

1. **Setup VSCode** - Prettier & ESLint akan auto-fix saat save
2. **Before Push** - Run `npm run lint:fix && npm run format`
3. **Never** - Jangan buat file baru dengan timestamp
4. **Review** - Lihat IMPORT_GUIDELINES.md untuk contoh code
5. **Git** - Gunakan git history, bukan file dengan timestamp

---

## ❓ FAQs

**Q: Bagaimana kalau butuh version history?**
A: Gunakan `git log` dan `git show`. Git akan track semua changes.

**Q: Apa bedanya .prettierrc dengan .eslintrc.json?**
A:

- Prettier = Formatter (cara code ditulis)
- ESLint = Linter (quality & best practice)

**Q: Harus install lebih banyak packages?**
A: Optional. Prettier & ESLint sudah dikonfigurasi. Tinggal jalankan jika sudah install.

**Q: Gimana kalau ada file lama yang duplikat?**
A: Hapus & gunakan yang final. Commit ke git.

---

## 🆘 TROUBLESHOOTING

### Error: "Type annotations can only be used in TypeScript files"

**Solusi:** Hapus `: type` dari parameters

```javascript
// ❌ SALAH
const getData = (id: string) => {}

// ✅ BENAR
const getData = (id) => {}
```

### Error: "Unexpected token" di file JSX

**Solusi:** Pastikan file tidak punya backtick markdown

````javascript
// ❌ SALAH
```jsx
import React from 'react';
...
````

// ✅ BENAR
import React from 'react';
...

````

### Prettier not formatting on save
**Solusi:** Check `.vscode/settings.json` ada di project
```json
{
  "[javascriptreact]": {
    "editor.defaultFormatter": "esbenp.prettier-vscode",
    "editor.formatOnSave": true
  }
}
````

---

## 📞 CONTACT

Untuk pertanyaan lebih lanjut, baca dokumentasi lengkap di:

- CLEANUP_REPORT.md (detail lengkap)
- IMPORT_GUIDELINES.md (contoh code)
- Prettier: https://prettier.io
- ESLint: https://eslint.org

---

**Happy Coding! 🚀**

Project Anda kini bersih, terstruktur, dan production-ready!
