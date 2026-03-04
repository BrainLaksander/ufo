import React, { useState, useMemo } from 'react';
import { useAuth } from '../../auth/useAuth';
import Card from '../../components/ui/Card';
import Badge from '../../components/ui/Badge';
import Dialog from '../../components/ui/Dialog';
import Tabs from '../../components/ui/Tabs';
import SearchInput from '../../components/ui/SearchInput';
import FilterChips from '../../components/ui/FilterChips';

/**
 * Lost & Found Page - Mahasiswa View
 *
 * Features:
 * - Tabs: Barang Hilang, Barang Ditemukan
 * - Search & filter by kategori
 * - Detail modal untuk lihat informasi lengkap
 * - Form lapor barang
 * - Priority section untuk barang penting
 * - Dummy data untuk development
 */
export default function LostAndFound() {
  const { user } = useAuth();
  const role = user ? user.role : null;

  // Dummy items
  const [items, setItems] = useState([
    {
      id: 1,
      name: 'Dompet Kulit Hitam',
      status: 'approved',
      itemStatus: 'hilang',
      priority: true,
      date: '2024-03-15',
      location: 'Aula Utama',
      description:
        'Dompet kulit hitam dengan inisial AB, berisi kartu pelajar, KTP, dan uang. Ditemukan di area depan aula sebelah tempat duduk besi.',
      contact: 'aldi.pratama@unklab.ac.id',
      phone: '0821-1234-5678',
      category: 'Dompet',
      image: null,
    },
    {
      id: 2,
      name: 'Gantungan Kunci Biru',
      status: 'approved',
      itemStatus: 'ditemukan',
      priority: false,
      date: '2024-03-10',
      location: 'Perpustakaan, Rak Buku Zona C',
      description:
        'Gantungan kunci biru dengan logo kampus UNKLAB, isi 3 kunci (warna emas). Sangat mudah dikenali.',
      contact: 'perpus@unklab.ac.id',
      phone: '0821-9876-5432',
      category: 'Kunci',
      image: null,
    },
    {
      id: 3,
      name: 'Buku Catatan MATH101',
      status: 'approved',
      itemStatus: 'hilang',
      priority: false,
      date: '2024-03-18',
      location: 'Ruang Kelas Lt. 2',
      description:
        'Buku catatan merah dengan nama "Sinta" di halaman depan. Isi catatan kuliah Matematika Dasar dari bulan Februari-Maret.',
      contact: 'sinta.wijaya@unklab.ac.id',
      phone: '0821-5555-5555',
      category: 'Buku',
      image: null,
    },
    {
      id: 4,
      name: 'Laptop Asus ROG',
      status: 'approved',
      itemStatus: 'hilang',
      priority: true,
      date: '2024-03-19',
      location: 'Ruang Laboratorium Komputer Lt. 4',
      description:
        'Laptop Asus ROG warna hitam dengan sticker logo. Casing dalam keadaan bagus, layar masih normal. Memiliki nilai tinggi.',
      contact: 'ricky.pram@unklab.ac.id',
      phone: '0821-2222-2222',
      category: 'Elektronik',
      image: null,
    },
    {
      id: 5,
      name: 'Kartu Pelajar Plastik',
      status: 'approved',
      itemStatus: 'ditemukan',
      priority: false,
      date: '2024-03-12',
      location: 'Kantin Area Meja Makan',
      description:
        'Kartu pelajar UNKLAB dengan foto nama. Ditemukan dalam kondisi baik di salah satu meja kantin.',
      contact: 'kemahasiswaan@unklab.ac.id',
      phone: '0821-3333-3333',
      category: 'Kartu Identitas',
      image: null,
    },
    {
      id: 6,
      name: 'Headphone Wireless JBL',
      status: 'approved',
      itemStatus: 'ditemukan',
      priority: false,
      date: '2024-03-08',
      location: 'Mushola Lt. 1',
      description:
        'Headphone nirkabel warna hitam dengan warna merah di bagian telinga. Masih dalam kondisi hidup dengan baterai 60%.',
      contact: 'doni.herm@unklab.ac.id',
      phone: '0821-4444-4444',
      category: 'Elektronik',
      image: null,
    },
  ]);

  // State
  const [activeTab, setActiveTab] = useState('hilang');
  const [searchQuery, setSearchQuery] = useState('');
  const [activeCategory, setActiveCategory] = useState('all');
  const [selectedItem, setSelectedItem] = useState(null);
  const [detailDialogOpen, setDetailDialogOpen] = useState(false);
  const [reportFormOpen, setReportFormOpen] = useState(false);

  // Form state
  const [form, setForm] = useState({
    name: '',
    category: 'Dompet',
    location: '',
    description: '',
    contact: '',
    phone: '',
    itemStatus: 'hilang',
  });
  const [formStatus, setFormStatus] = useState(null);

  // Tabs
  const tabs = [
    { id: 'hilang', label: 'Barang Hilang' },
    { id: 'ditemukan', label: 'Barang Ditemukan' },
  ];

  // Get unique categories
  const categories = useMemo(() => {
    const cats = new Set(['all']);
    items
      .filter((it) => it.status === 'approved')
      .forEach((it) => {
        if (it.category) cats.add(it.category);
      });
    return Array.from(cats).map((cat) => ({
      id: cat,
      label: cat === 'all' ? 'Semua Kategori' : cat,
    }));
  }, [items]);

  // Priority items
  const priorityItems = items.filter(
    (it) =>
      it.priority && it.status === 'approved' && it.itemStatus === 'hilang'
  );

  // Visible items dengan filter dan search
  const visibleItems = useMemo(() => {
    let filtered = items.filter((it) => it.status === 'approved');

    // Filter by tab
    filtered = filtered.filter((it) => it.itemStatus === activeTab);

    // Filter by search
    if (searchQuery.trim()) {
      const q = searchQuery.toLowerCase();
      filtered = filtered.filter(
        (it) =>
          it.name.toLowerCase().includes(q) ||
          it.description.toLowerCase().includes(q) ||
          it.location.toLowerCase().includes(q)
      );
    }

    // Filter by category
    if (activeCategory !== 'all') {
      filtered = filtered.filter((it) => it.category === activeCategory);
    }

    return filtered;
  }, [items, activeTab, searchQuery, activeCategory]);

  // Open detail handler
  const openDetail = (item) => {
    setSelectedItem(item);
    setDetailDialogOpen(true);
  };

  // Submit report handler
  const submitReport = (e) => {
    e.preventDefault();
    setFormStatus('loading');

    // Simulate API call
    setTimeout(() => {
      const newItem = {
        id: Date.now(),
        name: form.name,
        category: form.category,
        location: form.location,
        description: form.description,
        contact: form.contact,
        phone: form.phone,
        itemStatus: form.itemStatus,
        status: 'pending',
        priority: false,
        date: new Date().toISOString().split('T')[0],
        image: null,
      };

      setItems((prev) => [newItem, ...prev]);
      setFormStatus('success');

      // Reset form after 2 seconds
      setTimeout(() => {
        setForm({
          name: '',
          category: 'Dompet',
          location: '',
          description: '',
          contact: '',
          phone: '',
          itemStatus: 'hilang',
        });
        setFormStatus(null);
        setReportFormOpen(false);
      }, 2000);
    }, 1500);
  };

  // Render
  return (
    <div className="pt-20 pb-12">
      <div className="max-w-5xl mx-auto px-4">
        {/* Header */}
        <div className="mb-8">
          <h1 className="text-4xl font-bold text-purple-700 mb-2">
            🔍 Lost & Found Kampus
          </h1>
          <p className="text-gray-600 text-lg">
            Lapor barang hilang & temukan barang yang hilang. Hubungi pelapor
            jika menemukan barang Anda.
          </p>
        </div>

        {/* Priority Section */}
        {priorityItems.length > 0 && (
          <div className="mb-12">
            <h2 className="text-2xl font-bold text-red-700 mb-6">
              🔴 Barang Penting yang Hilang
            </h2>
            <p className="text-gray-600 mb-6">
              Barang-barang di bawah memiliki nilai penting. Jika Anda
              menemukan, segera hubungi pelapor.
            </p>
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              {priorityItems.map((item) => (
                <Card
                  key={item.id}
                  variant="highlight"
                  hover
                  onClick={() => openDetail(item)}
                  className="border-red-300 cursor-pointer relative"
                >
                  <Badge
                    variant="danger"
                    size="sm"
                    className="absolute top-4 right-4"
                  >
                    ⭐ Penting
                  </Badge>
                  <div className="text-5xl mb-3">
                    {item.category === 'Dompet' && '💼'}
                    {item.category === 'Kunci' && '🔑'}
                    {item.category === 'Buku' && '📖'}
                    {item.category === 'Elektronik' && '💻'}
                    {item.category === 'Kartu Identitas' && '🆔'}
                    {![
                      'Dompet',
                      'Kunci',
                      'Buku',
                      'Elektronik',
                      'Kartu Identitas',
                    ].includes(item.category) && '📦'}
                  </div>
                  <h3 className="text-lg font-bold text-gray-900">
                    {item.name}
                  </h3>
                  <p className="text-sm text-gray-600 mt-1 line-clamp-2">
                    {item.description}
                  </p>
                  <div className="mt-3 text-sm text-gray-700">
                    <p>📍 {item.location}</p>
                    <p>📅 {new Date(item.date).toLocaleDateString('id-ID')}</p>
                  </div>
                </Card>
              ))}
            </div>
          </div>
        )}

        {/* Search & Filter */}
        <div className="space-y-4 mb-8">
          <SearchInput
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            placeholder="Cari nama barang, lokasi, atau deskripsi..."
          />

          <div>
            <p className="text-sm font-semibold text-gray-700 mb-3">
              Kategori:
            </p>
            <FilterChips
              items={categories}
              selected={activeCategory}
              onSelect={setActiveCategory}
            />
          </div>
        </div>

        {/* Tabs */}
        <div className="mb-8">
          <Tabs tabs={tabs} activeTab={activeTab} onTabChange={setActiveTab} />
        </div>

        {/* Items Grid */}
        {visibleItems.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            {visibleItems.map((item) => (
              <Card
                key={item.id}
                hover
                onClick={() => openDetail(item)}
                className="flex flex-col cursor-pointer"
              >
                {/* Icon */}
                <div className="text-5xl mb-4">
                  {item.category === 'Dompet' && '💼'}
                  {item.category === 'Kunci' && '🔑'}
                  {item.category === 'Buku' && '📖'}
                  {item.category === 'Elektronik' && '💻'}
                  {item.category === 'Kartu Identitas' && '🆔'}
                  {![
                    'Dompet',
                    'Kunci',
                    'Buku',
                    'Elektronik',
                    'Kartu Identitas',
                  ].includes(item.category) && '📦'}
                </div>

                {/* Status Badge */}
                <div className="mb-3">
                  <Badge
                    variant={
                      item.itemStatus === 'ditemukan' ? 'success' : 'danger'
                    }
                    size="sm"
                  >
                    {item.itemStatus === 'ditemukan'
                      ? '✓ Ditemukan'
                      : '✗ Hilang'}
                  </Badge>
                </div>

                {/* Title & Category */}
                <h3 className="text-lg font-bold text-gray-900 mb-1">
                  {item.name}
                </h3>
                <Badge variant="purple" size="sm" className="w-fit mb-3">
                  {item.category}
                </Badge>

                {/* Description */}
                <p className="text-sm text-gray-600 line-clamp-2 mb-3 flex-1">
                  {item.description}
                </p>

                {/* Meta Info */}
                <div className="text-sm text-gray-700 space-y-1 py-3 border-t border-gray-200">
                  <p>📍 {item.location}</p>
                  <p>📅 {new Date(item.date).toLocaleDateString('id-ID')}</p>
                </div>

                {/* Action Button */}
                <button
                  onClick={(e) => {
                    e.stopPropagation();
                    openDetail(item);
                  }}
                  className="w-full mt-4 px-4 py-2 bg-purple-700 text-white rounded-lg hover:bg-purple-800 transition-colors font-semibold"
                >
                  Lihat Detail
                </button>
              </Card>
            ))}
          </div>
        ) : (
          <div className="text-center py-12 mb-12">
            <div className="text-4xl mb-3">📭</div>
            <p className="text-gray-600 text-lg">
              Tidak ada barang yang sesuai dengan filter Anda
            </p>
            <p className="text-gray-500 text-sm mt-2">
              Coba ubah kategori atau kata kunci pencarian
            </p>
          </div>
        )}

        {/* Report Form Section */}
        <Card>
          <h2 className="text-2xl font-bold text-gray-900 mb-6">
            📝 Laporkan Barang Hilang / Ditemukan
          </h2>
          <form onSubmit={submitReport} className="space-y-4">
            {/* Nama barang */}
            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-1">
                Nama Barang <span className="text-red-500">*</span>
              </label>
              <input
                type="text"
                placeholder="Contoh: Dompet Kulit Hitam, Laptop Asus ROG"
                value={form.name}
                onChange={(e) => setForm({ ...form, name: e.target.value })}
                required
                className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-700"
              />
            </div>

            {/* Kategori */}
            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-1">
                Kategori <span className="text-red-500">*</span>
              </label>
              <select
                value={form.category}
                onChange={(e) => setForm({ ...form, category: e.target.value })}
                className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-700"
              >
                <option value="Dompet">Dompet / Tas</option>
                <option value="Kunci">Kunci</option>
                <option value="Buku">Buku / Catatan</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Kartu Identitas">Kartu Identitas</option>
                <option value="Pakaian">Pakaian</option>
                <option value="Aksesoris">Aksesoris</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>

            {/* Item Status */}
            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-1">
                Status Barang <span className="text-red-500">*</span>
              </label>
              <div className="flex gap-4">
                <label className="flex items-center gap-2 cursor-pointer">
                  <input
                    type="radio"
                    name="itemStatus"
                    value="hilang"
                    checked={form.itemStatus === 'hilang'}
                    onChange={(e) =>
                      setForm({ ...form, itemStatus: e.target.value })
                    }
                  />
                  <span className="text-gray-700">Hilang</span>
                </label>
                <label className="flex items-center gap-2 cursor-pointer">
                  <input
                    type="radio"
                    name="itemStatus"
                    value="ditemukan"
                    checked={form.itemStatus === 'ditemukan'}
                    onChange={(e) =>
                      setForm({ ...form, itemStatus: e.target.value })
                    }
                  />
                  <span className="text-gray-700">Ditemukan</span>
                </label>
              </div>
            </div>

            {/* Lokasi */}
            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-1">
                Lokasi <span className="text-red-500">*</span>
              </label>
              <input
                type="text"
                placeholder="Contoh: Aula Utama, Perpustakaan Lt. 2, Kantin Area Utama"
                value={form.location}
                onChange={(e) => setForm({ ...form, location: e.target.value })}
                required
                className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-700"
              />
            </div>

            {/* Deskripsi */}
            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-1">
                Deskripsi Lengkap <span className="text-red-500">*</span>
              </label>
              <textarea
                placeholder="Jelaskan karakteristik barang, warna, ciri khas, apa yang berisi, kondisi, dll. Semakin detail semakin baik!"
                value={form.description}
                onChange={(e) =>
                  setForm({ ...form, description: e.target.value })
                }
                required
                rows={4}
                className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-700 resize-none"
              />
            </div>

            {/* Email */}
            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-1">
                Email <span className="text-red-500">*</span>
              </label>
              <input
                type="email"
                placeholder="email@unklab.ac.id"
                value={form.contact}
                onChange={(e) => setForm({ ...form, contact: e.target.value })}
                required
                className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-700"
              />
            </div>

            {/* Telepon */}
            <div>
              <label className="block text-sm font-semibold text-gray-700 mb-1">
                Nomor Telepon <span className="text-red-500">*</span>
              </label>
              <input
                type="tel"
                placeholder="0821-XXXX-XXXX"
                value={form.phone}
                onChange={(e) => setForm({ ...form, phone: e.target.value })}
                required
                className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-700"
              />
            </div>

            {/* Submit Button */}
            <button
              type="submit"
              disabled={formStatus === 'loading'}
              className={`w-full px-6 py-3 rounded-lg font-semibold transition-colors ${
                formStatus === 'loading'
                  ? 'bg-gray-400 text-gray-200 cursor-not-allowed'
                  : formStatus === 'success'
                    ? 'bg-green-500 text-white'
                    : 'bg-purple-700 text-white hover:bg-purple-800'
              }`}
            >
              {formStatus === 'loading'
                ? '⏳ Mengirim...'
                : formStatus === 'success'
                  ? '✓ Laporan Terkirim!'
                  : '📤 Kirim Laporan'}
            </button>

            {/* Info */}
            <div className="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400 text-sm text-gray-700">
              <p>
                <strong>ℹ️ Catatan:</strong> Laporan Anda akan diverifikasi oleh
                admin sebelum ditampilkan. Pastikan data kontak Anda benar agar
                pelapor bisa menghubungi.
              </p>
            </div>
          </form>
        </Card>
      </div>

      {/* Detail Modal */}
      <Dialog
        open={detailDialogOpen}
        onClose={() => setDetailDialogOpen(false)}
        title={selectedItem?.name}
        size="lg"
      >
        {selectedItem && (
          <div className="space-y-6">
            {/* Icon & Status */}
            <div className="flex items-start justify-between gap-4">
              <div className="text-6xl">
                {selectedItem.category === 'Dompet' && '💼'}
                {selectedItem.category === 'Kunci' && '🔑'}
                {selectedItem.category === 'Buku' && '📖'}
                {selectedItem.category === 'Elektronik' && '💻'}
                {selectedItem.category === 'Kartu Identitas' && '🆔'}
                {![
                  'Dompet',
                  'Kunci',
                  'Buku',
                  'Elektronik',
                  'Kartu Identitas',
                ].includes(selectedItem.category) && '📦'}
              </div>
              <div className="flex gap-2">
                <Badge
                  variant={
                    selectedItem.itemStatus === 'ditemukan'
                      ? 'success'
                      : 'danger'
                  }
                  size="md"
                >
                  {selectedItem.itemStatus === 'ditemukan'
                    ? '✓ Ditemukan'
                    : '✗ Hilang'}
                </Badge>
                {selectedItem.priority && (
                  <Badge variant="warning" size="md">
                    ⭐ Penting
                  </Badge>
                )}
              </div>
            </div>

            {/* Info */}
            <div className="space-y-3">
              <div>
                <p className="text-sm font-semibold text-gray-600">Kategori</p>
                <p className="text-gray-900">{selectedItem.category}</p>
              </div>
              <div>
                <p className="text-sm font-semibold text-gray-600">Lokasi</p>
                <p className="text-gray-900">{selectedItem.location}</p>
              </div>
              <div>
                <p className="text-sm font-semibold text-gray-600">Tanggal</p>
                <p className="text-gray-900">
                  {new Date(selectedItem.date).toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                  })}
                </p>
              </div>
            </div>

            {/* Deskripsi */}
            <div>
              <p className="text-sm font-semibold text-gray-600 mb-2">
                Deskripsi Lengkap
              </p>
              <p className="text-gray-700 leading-relaxed whitespace-pre-line">
                {selectedItem.description}
              </p>
            </div>

            {/* Contact Section */}
            <div className="pt-4 border-t border-gray-200 space-y-3">
              <p className="font-semibold text-gray-900">Hubungi Pelapor</p>
              <div className="space-y-2">
                <a
                  href={`mailto:${selectedItem.contact}`}
                  className="block px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-semibold text-sm"
                >
                  ✉️ Email: {selectedItem.contact}
                </a>
                <a
                  href={`https://wa.me/62${selectedItem.phone.substring(1)}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="block px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors font-semibold text-sm"
                >
                  💬 Whatsapp: {selectedItem.phone}
                </a>
              </div>
            </div>

            {/* Security Info */}
            <div className="bg-red-50 p-4 rounded-lg border-l-4 border-red-400 text-sm text-gray-700">
              <p>
                <strong>⚠️ Keamanan:</strong> Jangan bertemu sendirian saat
                mengambil barang. Sebaiknya bertemu di tempat umum dan ramai.
                Pastikan barang yang Anda ambil sesuai dengan deskripsi.
              </p>
            </div>

            {/* Close Button */}
            <button
              onClick={() => setDetailDialogOpen(false)}
              className="w-full px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors font-semibold"
            >
              Tutup
            </button>
          </div>
        )}
      </Dialog>
    </div>
  );
}
