import React, { useState } from 'react';
import {
  FileText,
  Upload,
  Download,
  Eye,
  Trash2,
  X,
  Plus,
  Calendar,
} from 'lucide-react';

/**
 * Proposal & Archive Page - Pengurus
 *
 * Halaman untuk mengelola dokumen proposal dan arsip internal organisasi
 */
export default function Proposal() {
  const [documents, setDocuments] = useState([
    {
      id: 1,
      title: 'Proposal Kegiatan Sosialisasi 2026',
      type: 'proposal',
      status: 'approved',
      uploadDate: '2026-02-15',
      uploadedBy: 'Ahmad Rifki',
      fileSize: '2.5 MB',
      description:
        'Proposal untuk kegiatan sosialisasi organisasi di semester 2',
    },
    {
      id: 2,
      title: 'Laporan Kegiatan Tahun 2025',
      type: 'report',
      status: 'approved',
      uploadDate: '2026-02-20',
      uploadedBy: 'Siti Nurhaliza',
      fileSize: '3.8 MB',
      description: 'Laporan komprehensif kegiatan organisasi tahun 2025',
    },
    {
      id: 3,
      title: 'Rencana Strategis 2026-2027',
      type: 'proposal',
      status: 'draft',
      uploadDate: '2026-02-25',
      uploadedBy: 'Budi Santoso',
      fileSize: '1.2 MB',
      description: 'Rencana strategis jangka panjang organisasi',
    },
    {
      id: 4,
      title: 'Foto-Foto Event Tahun Lalu',
      type: 'archive',
      status: 'approved',
      uploadDate: '2026-01-30',
      uploadedBy: 'Maya Putri',
      fileSize: '15 MB',
      description: 'Koleksi foto dokumentasi event 2024-2025',
    },
  ]);

  const [showUploadForm, setShowUploadForm] = useState(false);
  const [filterType, setFilterType] = useState('all');
  const [filterStatus, setFilterStatus] = useState('all');
  const [formData, setFormData] = useState({
    title: '',
    type: 'proposal',
    description: '',
  });

  const filteredDocuments = documents.filter((doc) => {
    let match = true;
    if (filterType !== 'all' && doc.type !== filterType) match = false;
    if (filterStatus !== 'all' && doc.status !== filterStatus) match = false;
    return match;
  });

  const handleUpload = () => {
    if (!formData.title) {
      alert('Judul dokumen tidak boleh kosong');
      return;
    }

    const newDoc = {
      id: Math.max(...documents.map((d) => d.id), 0) + 1,
      title: formData.title,
      type: formData.type,
      status: 'draft',
      uploadDate: new Date().toISOString().split('T')[0],
      uploadedBy: 'Pengurus Saat Ini',
      fileSize: '0 MB',
      description: formData.description,
    };

    setDocuments([newDoc, ...documents]);
    setFormData({ title: '', type: 'proposal', description: '' });
    setShowUploadForm(false);
    alert('Dokumen berhasil diunggah');
  };

  const handleDelete = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
      setDocuments(documents.filter((d) => d.id !== id));
    }
  };

  const handleApprove = (id) => {
    setDocuments(
      documents.map((d) => (d.id === id ? { ...d, status: 'approved' } : d))
    );
  };

  const handleDownload = (title) => {
    alert(`Download dimulai untuk: ${title}`);
  };

  const StatCard = ({ icon: Icon, label, value, color }) => (
    <div className="bg-white p-4 rounded-xl border-2 border-gray-200">
      <div className="flex items-center gap-3">
        <div className={`p-3 rounded-lg ${color}`}>
          <Icon size={24} />
        </div>
        <div>
          <p className="text-xs text-gray-500 uppercase">{label}</p>
          <p className="text-2xl font-bold text-gray-900">{value}</p>
        </div>
      </div>
    </div>
  );

  const docTypeLabels = {
    proposal: 'Proposal',
    report: 'Laporan',
    archive: 'Arsip',
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Proposal & Arsip</h1>
          <p className="text-gray-600 mt-1">
            Kelola dokumen dan arsip internal organisasi
          </p>
        </div>
        <button
          onClick={() => setShowUploadForm(true)}
          className="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold border-2 border-blue-700"
        >
          <Plus size={20} />
          Upload Dokumen
        </button>
      </div>

      {/* Statistics */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <StatCard
          icon={FileText}
          label="Total Dokumen"
          value={documents.length}
          color="bg-blue-100 text-blue-700"
        />
        <StatCard
          icon={FileText}
          label="Proposal"
          value={documents.filter((d) => d.type === 'proposal').length}
          color="bg-purple-100 text-purple-700"
        />
        <StatCard
          icon={FileText}
          label="Laporan"
          value={documents.filter((d) => d.type === 'report').length}
          color="bg-green-100 text-green-700"
        />
        <StatCard
          icon={FileText}
          label="Approved"
          value={documents.filter((d) => d.status === 'approved').length}
          color="bg-yellow-100 text-yellow-700"
        />
      </div>

      {/* Filter Tabs */}
      <div className="bg-white p-6 rounded-2xl border-2 border-gray-200">
        <div className="space-y-4">
          {/* Type Filter */}
          <div className="flex gap-2 flex-wrap items-center">
            <span className="font-semibold text-gray-900">Tipe:</span>
            {['all', 'proposal', 'report', 'archive'].map((type) => (
              <button
                key={type}
                onClick={() => setFilterType(type)}
                className={`px-4 py-2 rounded-lg font-semibold text-sm border-2 transition-colors ${
                  filterType === type
                    ? 'bg-blue-100 text-blue-700 border-blue-300'
                    : 'bg-gray-100 text-gray-700 border-gray-300 hover:border-blue-300'
                }`}
              >
                {type === 'all'
                  ? 'Semua'
                  : type === 'proposal'
                    ? 'Proposal'
                    : type === 'report'
                      ? 'Laporan'
                      : 'Arsip'}
              </button>
            ))}
          </div>

          {/* Status Filter */}
          <div className="flex gap-2 flex-wrap items-center">
            <span className="font-semibold text-gray-900">Status:</span>
            {['all', 'draft', 'approved'].map((status) => (
              <button
                key={status}
                onClick={() => setFilterStatus(status)}
                className={`px-4 py-2 rounded-lg font-semibold text-sm border-2 transition-colors ${
                  filterStatus === status
                    ? 'bg-blue-100 text-blue-700 border-blue-300'
                    : 'bg-gray-100 text-gray-700 border-gray-300 hover:border-blue-300'
                }`}
              >
                {status === 'all'
                  ? 'Semua'
                  : status === 'draft'
                    ? 'Draft'
                    : 'Approved'}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Documents List */}
      <div className="space-y-4">
        {filteredDocuments.length === 0 ? (
          <div className="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
            <FileText className="mx-auto mb-3 text-gray-400" size={48} />
            <p className="text-gray-600 font-semibold">Tidak ada dokumen</p>
          </div>
        ) : (
          filteredDocuments.map((doc) => (
            <div
              key={doc.id}
              className="bg-white p-6 rounded-2xl border-2 border-gray-200 hover:shadow-lg transition-shadow"
            >
              <div className="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div className="md:col-span-2">
                  <div className="flex items-start gap-3">
                    <div className="p-3 bg-blue-100 rounded-lg">
                      <FileText className="text-blue-700" size={24} />
                    </div>
                    <div>
                      <h3 className="text-lg font-bold text-gray-900">
                        {doc.title}
                      </h3>
                      <p className="text-sm text-gray-600 mt-1">
                        {doc.description}
                      </p>
                    </div>
                  </div>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase">Tipe</p>
                  <span className="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-bold border-2 border-purple-300 inline-block mt-1">
                    {docTypeLabels[doc.type]}
                  </span>
                </div>

                <div>
                  <p className="text-xs text-gray-500 uppercase">Status</p>
                  <span
                    className={`px-3 py-1 rounded-full text-sm font-bold border-2 inline-block mt-1 ${
                      doc.status === 'approved'
                        ? 'bg-green-100 text-green-700 border-green-300'
                        : 'bg-yellow-100 text-yellow-700 border-yellow-300'
                    }`}
                  >
                    {doc.status === 'approved' ? 'Approved' : 'Draft'}
                  </span>
                </div>

                <div className="flex gap-2 flex-wrap justify-end">
                  <button
                    onClick={() => handleDownload(doc.title)}
                    className="flex items-center gap-2 px-3 py-2 text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors font-semibold border-2 border-green-300 text-sm"
                  >
                    <Download size={14} />
                    Download
                  </button>
                  <button
                    onClick={() => handleDelete(doc.id)}
                    className="flex items-center gap-2 px-3 py-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors font-semibold border-2 border-red-300 text-sm"
                  >
                    <Trash2 size={14} />
                    Hapus
                  </button>
                </div>
              </div>

              <div className="border-t-2 border-gray-200 pt-3 flex items-center justify-between flex-wrap gap-2">
                <div className="text-xs text-gray-500">
                  <span className="flex items-center gap-1">
                    <Calendar size={14} />
                    Upload: {doc.uploadDate} • {doc.uploadedBy}
                  </span>
                </div>

                {doc.status === 'draft' && (
                  <button
                    onClick={() => handleApprove(doc.id)}
                    className="px-4 py-2 text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors font-semibold border-2 border-green-300 text-sm"
                  >
                    Approve
                  </button>
                )}
              </div>
            </div>
          ))
        )}
      </div>

      {/* Upload Form Modal */}
      {showUploadForm && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl border-2 border-gray-300 max-w-md w-full shadow-2xl">
            <div className="border-b-2 border-gray-200 p-6 flex items-center justify-between">
              <h2 className="text-2xl font-bold text-gray-900">
                Upload Dokumen
              </h2>
              <button
                onClick={() => setShowUploadForm(false)}
                className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
              >
                <X size={24} />
              </button>
            </div>

            <div className="p-6 space-y-4">
              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Judul Dokumen *
                </label>
                <input
                  type="text"
                  value={formData.title}
                  onChange={(e) =>
                    setFormData({ ...formData, title: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                  placeholder="Masukkan judul dokumen"
                />
              </div>

              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Tipe Dokumen
                </label>
                <select
                  value={formData.type}
                  onChange={(e) =>
                    setFormData({ ...formData, type: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                >
                  <option value="proposal">Proposal</option>
                  <option value="report">Laporan</option>
                  <option value="archive">Arsip</option>
                </select>
              </div>

              <div>
                <label className="block text-sm font-bold text-gray-900 mb-2">
                  Deskripsi
                </label>
                <textarea
                  value={formData.description}
                  onChange={(e) =>
                    setFormData({ ...formData, description: e.target.value })
                  }
                  className="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                  placeholder="Deskripsi dokumen (opsional)"
                  rows={3}
                />
              </div>

              {/* File Upload Area */}
              <div className="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer">
                <Upload className="mx-auto mb-2 text-gray-400" size={28} />
                <p className="text-gray-600 font-semibold">
                  Klik untuk upload atau drag & drop
                </p>
                <p className="text-xs text-gray-500 mt-1">
                  PDF, DOC, XLS, ZIP (max 50MB)
                </p>
              </div>

              <div className="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button
                  onClick={handleUpload}
                  className="flex-1 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold border-2 border-blue-700"
                >
                  Upload
                </button>
                <button
                  onClick={() => setShowUploadForm(false)}
                  className="flex-1 px-6 py-2 bg-gray-100 text-gray-900 rounded-lg hover:bg-gray-200 transition-colors font-semibold border-2 border-gray-300"
                >
                  Batal
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
