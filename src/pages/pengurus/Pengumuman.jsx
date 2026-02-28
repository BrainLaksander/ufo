import React, { useState } from 'react';
import { Bell, Plus, Edit2, Trash2, Eye, X, Calendar } from 'lucide-react';

/**
 * Pengumuman Management Page - Pengurus
 *
 * Halaman untuk mengelola pengumuman organisasi
 */
export default function Pengumuman() {
  const [announcements, setAnnouncements] = useState([
    {
      id: 1,
      title: 'Pembukaan Pendaftaran Anggota Baru',
      category: 'Pendaftaran',
      content:
        'Kami membuka kesempatan bagi mahasiswa baru untuk bergabung dengan organisasi kami. Periode pendaftaran berlangsung hingga 15 Maret 2026.',
      status: 'published',
      publishedDate: '2026-02-28',
      createdDate: '2026-02-27',
      attachment: null,
    },
    {
      id: 2,
      title: 'Jadwal Rapat Rutin Bulan Maret',
      category: 'Jadwal',
      content:
        'Rapat rutin bulan Maret akan diadakan setiap hari Rabu pukul 15.00 di Ruang Rapat Lantai 2.',
      status: 'published',
      publishedDate: '2026-02-25',
      createdDate: '2026-02-24',
      attachment: null,
    },
    {
      id: 3,
      title: 'Update Sistem Manajemen Event',
      category: 'Update',
      content:
        'Sistem untuk mengelola event telah diperbarui dengan fitur-fitur baru untuk kemudahan pengelolaan.',
      status: 'draft',
      publishedDate: null,
      createdDate: '2026-02-28',
      attachment: null,
    },
  ]);

  const [showForm, setShowForm] = useState(false);
  const [showDetail, setShowDetail] = useState(false);
  const [selectedAnnouncement, setSelectedAnnouncement] = useState(null);
  const [editingId, setEditingId] = useState(null);
  const [statusFilter, setStatusFilter] = useState('all');
  const [formData, setFormData] = useState({
    title: '',
    category: 'Update',
    content: '',
    status: 'draft',
    attachment: null,
  });

  const filteredAnnouncements =
    statusFilter === 'all'
      ? announcements
      : announcements.filter((a) => a.status === statusFilter);

  const categories = [
    'Pengumuman',
    'Jadwal',
    'Pendaftaran',
    'Event',
    'Update',
    'Lainnya',
  ];

  const openFormCreate = () => {
    setFormData({
      title: '',
      category: 'Update',
      content: '',
      status: 'draft',
      attachment: null,
    });
    setEditingId(null);
    setShowForm(true);
  };

  const openFormEdit = (announcement) => {
    setFormData({
      title: announcement.title,
      category: announcement.category,
      content: announcement.content,
      status: announcement.status,
      attachment: announcement.attachment,
    });
    setEditingId(announcement.id);
    setShowForm(true);
  };

  const handleSave = () => {
    if (!formData.title || !formData.content) {
      alert('Judul dan konten tidak boleh kosong');
      return;
    }

    if (editingId) {
      setAnnouncements(
        announcements.map((a) =>
          a.id === editingId
            ? {
                ...a,
                title: formData.title,
                category: formData.category,
                content: formData.content,
                status: formData.status,
              }
            : a
        )
      );
    } else {
      const newAnnouncement = {
        id: Math.max(...announcements.map((a) => a.id), 0) + 1,
        title: formData.title,
        category: formData.category,
        content: formData.content,
        status: formData.status,
        publishedDate:
          formData.status === 'published'
            ? new Date().toISOString().split('T')[0]
            : null,
        createdDate: new Date().toISOString().split('T')[0],
        attachment: formData.attachment,
      };
      setAnnouncements([newAnnouncement, ...announcements]);
    }

    setShowForm(false);
  };

  const handlePublish = (id) => {
    setAnnouncements(
      announcements.map((a) =>
        a.id === id
          ? {
              ...a,
              status: 'published',
              publishedDate: new Date().toISOString().split('T')[0],
            }
          : a
      )
    );
  };

  const handleDelete = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')) {
      setAnnouncements(announcements.filter((a) => a.id !== id));
    }
  };

  const viewDetail = (announcement) => {
    setSelectedAnnouncement(announcement);
    setShowDetail(true);
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Pengumuman</h1>
          <p className="text-gray-600 mt-1">
            Kelola pengumuman untuk anggota organisasi
          </p>
        </div>
        <button
          onClick={openFormCreate}
          className="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold border-2 border-blue-700"
        >
          <Plus size={20} />
          Buat Pengumuman
        </button>
      </div>

      {/* Filter Tabs */}
      <div className="flex gap-2 border-b-2 border-gray-200 overflow-x-auto pb-0">
        {['all', 'published', 'draft'].map((status) => (
          <button
            key={status}
            onClick={() => setStatusFilter(status)}
            className={`px-4 py-3 font-semibold text-sm border-b-2 transition-colors whitespace-nowrap ${
              statusFilter === status
                ? 'border-blue-600 text-blue-600'
                : 'border-transparent text-gray-600 hover:text-gray-900'
            }`}
          >
            {status === 'all'
              ? 'Semua'
              : status === 'published'
                ? 'Dipublikasikan'
                : 'Draft'}
            {status !== 'all' && (
              <span className="ml-2 text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full">
                {announcements.filter((a) => a.status === status).length}
              </span>
            )}
          </button>
        ))}
      </div>

      {/* Announcements List */}
      <div className="space-y-4">
        {filteredAnnouncements.length === 0 ? (
          <div className="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
            <Bell className="mx-auto mb-3 text-gray-400" size={48} />
            <p className="text-gray-600 font-semibold">Tidak ada pengumuman</p>
            <p className="text-gray-500 text-sm mt-1">
              Buat pengumuman baru untuk berbagi informasi
            </p>
          </div>
        ) : (
          filteredAnnouncements.map((announcement) => (
            <div
              key={announcement.id}
              className="bg-white p-6 rounded-2xl border-2 border-gray-200 hover:shadow-lg transition-shadow"
            >
              <div className="flex items-start justify-between mb-3">
                <div className="flex-1">
                  <h3 className="text-lg font-bold text-gray-900">
                    {announcement.title}
                  </h3>
                  <div className="flex items-center gap-3 mt-2 flex-wrap">
                    <span className="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold border-2 border-gray-300">
                      {announcement.category}
                    </span>
                    <span
                      className={`px-3 py-1 rounded-full text-xs font-bold border-2 ${
                        announcement.status === 'published'
                          ? 'bg-green-100 text-green-700 border-green-300'
                          : 'bg-yellow-100 text-yellow-700 border-yellow-300'
                      }`}
                    >
                      {announcement.status === 'published'
                        ? 'Dipublikasikan'
                        : 'Draft'}
                    </span>
                  </div>
                </div>
              </div>

              <p className="text-gray-600 mb-4 line-clamp-2">
                {announcement.content}
              </p>

              <div className="flex items-center gap-4 text-xs text-gray-500 mb-4 pb-4 border-b-2 border-gray-200">
                <span className="flex items-center gap-1">
                  <Calendar size={14} />
                  Created: {announcement.createdDate}
                </span>
                {announcement.publishedDate && (
                  <span className="flex items-center gap-1">
                    <Calendar size={14} />
                    Published: {announcement.publishedDate}
                  </span>
                )}
              </div>

              {/* Actions */}
              <div className="flex gap-2 flex-wrap">
                <button
                  onClick={() => viewDetail(announcement)}
                  className="flex items-center gap-2 px-4 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors font-semibold border-2 border-blue-300"
                >
                  <Eye size={16} />
                  Lihat Detail
                </button>
                <button
                  onClick={() => openFormEdit(announcement)}
                  className="flex items-center gap-2 px-4 py-2 text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors font-semibold border-2 border-amber-300"
                >
                  <Edit2 size={16} />
                  Edit
                </button>
                {announcement.status === 'draft' && (
                  <button
                    onClick={() => handlePublish(announcement.id)}
                    className="flex items-center gap-2 px-4 py-2 text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors font-semibold border-2 border-green-300"
                  >
                    <Bell size={16} />
                    Publikasikan
                  </button>
                )}
                <button
                  onClick={() => handleDelete(announcement.id)}
                  className="flex items-center gap-2 px-4 py-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors font-semibold border-2 border-red-300"
                >
                  <Trash2 size={16} />
                  Hapus
                </button>
              </div>
            </div>
          ))
        )}
      </div>

      {/* Form Modal */}
      {showForm && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border-2 border-gray-300 max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <div className="sticky top-0 bg-white border-b-2 border-gray-200 p-6 flex items-center justify-between">
              <h2 className="text-2xl font-bold text-gray-900">
                {editingId ? 'Edit Pengumuman' : 'Buat Pengumuman Baru'}
              </h2>
              <button
                onClick={() => setShowForm(false)}
                className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
              >
                <X size={24} />
              </button>
            </div>

            <div className="p-6 space-y-4">
              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Judul Pengumuman *
                </label>
                <input
                  type="text"
                  value={formData.title}
                  onChange={(e) =>
                    setFormData({ ...formData, title: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                  placeholder="Masukkan judul pengumuman"
                />
              </div>

              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Kategori *
                </label>
                <select
                  value={formData.category}
                  onChange={(e) =>
                    setFormData({ ...formData, category: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                >
                  {categories.map((cat) => (
                    <option key={cat} value={cat}>
                      {cat}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Konten Pengumuman *
                </label>
                <textarea
                  value={formData.content}
                  onChange={(e) =>
                    setFormData({ ...formData, content: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                  placeholder="Tulis konten pengumuman di sini"
                  rows={8}
                />
              </div>

              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Status Publikasi
                </label>
                <select
                  value={formData.status}
                  onChange={(e) =>
                    setFormData({ ...formData, status: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                >
                  <option value="draft">Simpan sebagai Draft</option>
                  <option value="published">Publikasikan sekarang</option>
                </select>
              </div>

              <div className="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button
                  onClick={handleSave}
                  className="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold border-2 border-blue-700"
                >
                  {editingId ? 'Simpan Perubahan' : 'Buat Pengumuman'}
                </button>
                <button
                  onClick={() => setShowForm(false)}
                  className="flex-1 px-6 py-3 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition-colors font-semibold border-2 border-gray-300"
                >
                  Batal
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Detail Modal */}
      {showDetail && selectedAnnouncement && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border-2 border-gray-300 max-w-2xl w-full shadow-2xl">
            <div className="border-b-2 border-gray-200 p-6 flex items-center justify-between">
              <h2 className="text-2xl font-bold text-gray-900">
                {selectedAnnouncement.title}
              </h2>
              <button
                onClick={() => setShowDetail(false)}
                className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
              >
                <X size={24} />
              </button>
            </div>

            <div className="p-6 space-y-4">
              <div className="flex items-center gap-3 flex-wrap">
                <span className="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold border-2 border-gray-300">
                  {selectedAnnouncement.category}
                </span>
                <span
                  className={`px-3 py-1 rounded-full text-xs font-bold border-2 ${
                    selectedAnnouncement.status === 'published'
                      ? 'bg-green-100 text-green-700 border-green-300'
                      : 'bg-yellow-100 text-yellow-700 border-yellow-300'
                  }`}
                >
                  {selectedAnnouncement.status === 'published'
                    ? 'Dipublikasikan'
                    : 'Draft'}
                </span>
              </div>

              <div className="bg-gray-50 p-4 rounded-lg border-2 border-gray-200">
                <p className="text-gray-900 whitespace-pre-wrap">
                  {selectedAnnouncement.content}
                </p>
              </div>

              <div className="flex items-center gap-4 text-sm text-gray-600">
                <span className="flex items-center gap-2">
                  <Calendar size={16} />
                  Created: {selectedAnnouncement.createdDate}
                </span>
                {selectedAnnouncement.publishedDate && (
                  <span className="flex items-center gap-2">
                    <Calendar size={16} />
                    Published: {selectedAnnouncement.publishedDate}
                  </span>
                )}
              </div>

              <div className="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button
                  onClick={() => setShowDetail(false)}
                  className="flex-1 px-6 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-semibold border-2 border-blue-300"
                >
                  Tutup
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
