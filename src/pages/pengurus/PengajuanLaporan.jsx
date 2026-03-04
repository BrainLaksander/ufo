import React, { useState } from 'react';
import {
  FileText,
  CheckCircle,
  AlertCircle,
  Clock,
  Eye,
  Download,
} from 'lucide-react';

export default function PengajuanLaporan() {
  const [activeTab, setActiveTab] = useState('submission');

  // Dummy data
  const submissions = [
    {
      id: 1,
      title: 'Laporan Event Workshop Python',
      date: '2024-03-15',
      status: 'approved',
      type: 'Laporan Event',
      submittedBy: 'Ahmad Rifki',
    },
    {
      id: 2,
      title: 'Pengajuan Dana Event Gathering BEM',
      date: '2024-03-10',
      status: 'pending',
      type: 'Pengajuan Dana',
      submittedBy: 'Siti Nurhaliza',
    },
    {
      id: 3,
      title: 'Laporan Pertanggungjawaban Event Seminar',
      date: '2024-03-05',
      status: 'revision',
      type: 'Laporan Keuangan',
      submittedBy: 'Budi Santoso',
    },
  ];

  const reports = [
    {
      id: 1,
      title: 'Laporan Bulanan Organisasi Maret 2024',
      date: '2024-03-31',
      status: 'approved',
      period: 'Maret 2024',
      fileSize: '2.4 MB',
    },
    {
      id: 2,
      title: 'Laporan Keuangan Kuartal I 2024',
      date: '2024-03-20',
      status: 'approved',
      period: 'Q1 2024',
      fileSize: '1.8 MB',
    },
    {
      id: 3,
      title: 'Laporan Bulanan Organisasi Februari 2024',
      date: '2024-02-28',
      status: 'approved',
      period: 'Februari 2024',
      fileSize: '2.1 MB',
    },
  ];

  const getStatusBadge = (status) => {
    const statusMap = {
      approved: {
        bg: 'bg-green-100',
        text: 'text-green-700',
        label: 'Disetujui',
      },
      pending: {
        bg: 'bg-yellow-100',
        text: 'text-yellow-700',
        label: 'Menunggu',
      },
      revision: { bg: 'bg-red-100', text: 'text-red-700', label: 'Revisi' },
    };
    const style = statusMap[status] || statusMap.pending;
    return style;
  };

  const getStatusIcon = (status) => {
    switch (status) {
      case 'approved':
        return <CheckCircle className="w-4 h-4" />;
      case 'pending':
        return <Clock className="w-4 h-4" />;
      case 'revision':
        return <AlertCircle className="w-4 h-4" />;
      default:
        return <FileText className="w-4 h-4" />;
    }
  };

  return (
    <div className="w-full bg-white min-h-screen">
      {/* Header */}
      <div className="max-w-7xl mx-auto px-6 py-10">
        <h1 className="text-3xl font-bold text-gray-900 mb-2">
          Pengajuan & Laporan
        </h1>
        <p className="text-gray-600 text-sm">
          Kelola pengajuan dan laporan organisasi Anda
        </p>
      </div>

      {/* Main Content */}
      <div className="max-w-7xl mx-auto px-6 pb-12 space-y-6">
        {/* Tab Navigation */}
        <div className="bg-gray-100 p-1 rounded-xl inline-flex">
          <button
            onClick={() => setActiveTab('submission')}
            className={`px-6 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 ${
              activeTab === 'submission'
                ? 'bg-white shadow-sm text-[#663399]'
                : 'text-gray-500 hover:text-gray-700'
            }`}
          >
            Pengajuan
          </button>
          <button
            onClick={() => setActiveTab('report')}
            className={`px-6 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 ${
              activeTab === 'report'
                ? 'bg-white shadow-sm text-[#663399]'
                : 'text-gray-500 hover:text-gray-700'
            }`}
          >
            Laporan
          </button>
        </div>

        {/* Submissions Tab */}
        {activeTab === 'submission' && (
          <div className="space-y-6">
            {submissions.length > 0 ? (
              submissions.map((item) => {
                const statusStyle = getStatusBadge(item.status);
                return (
                  <div
                    key={item.id}
                    className="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
                  >
                    <div className="flex items-start justify-between gap-4">
                      <div className="flex-1">
                        <div className="flex items-center gap-3 mb-2">
                          <FileText className="w-5 h-5 text-[#663399]" />
                          <h3 className="text-lg font-semibold text-gray-900">
                            {item.title}
                          </h3>
                        </div>

                        <div className="flex flex-wrap gap-4 mt-4 text-sm text-gray-600">
                          <div className="flex items-center gap-2">
                            <span className="text-gray-500">Jenis:</span>
                            <span className="font-medium text-gray-900">
                              {item.type}
                            </span>
                          </div>
                          <div className="flex items-center gap-2">
                            <span className="text-gray-500">Tanggal:</span>
                            <span className="font-medium text-gray-900">
                              {item.date}
                            </span>
                          </div>
                          <div className="flex items-center gap-2">
                            <span className="text-gray-500">
                              Pengajuan oleh:
                            </span>
                            <span className="font-medium text-gray-900">
                              {item.submittedBy}
                            </span>
                          </div>
                        </div>
                      </div>

                      <div className="flex flex-col items-end gap-3">
                        <span
                          className={`inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium ${statusStyle.bg} ${statusStyle.text}`}
                        >
                          {getStatusIcon(item.status)}
                          {statusStyle.label}
                        </span>

                        <div className="flex gap-2">
                          <button className="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <Eye className="w-4 h-4 text-gray-600" />
                          </button>
                          <button className="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <Download className="w-4 h-4 text-gray-600" />
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                );
              })
            ) : (
              <div className="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <FileText className="w-8 h-8 text-gray-400 mx-auto mb-3" />
                <p className="text-gray-600 text-sm">Belum ada pengajuan</p>
              </div>
            )}
          </div>
        )}

        {/* Reports Tab */}
        {activeTab === 'report' && (
          <div className="space-y-6">
            {reports.length > 0 ? (
              reports.map((item) => {
                const statusStyle = getStatusBadge(item.status);
                return (
                  <div
                    key={item.id}
                    className="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
                  >
                    <div className="flex items-start justify-between gap-4">
                      <div className="flex-1">
                        <div className="flex items-center gap-3 mb-2">
                          <FileText className="w-5 h-5 text-[#663399]" />
                          <h3 className="text-lg font-semibold text-gray-900">
                            {item.title}
                          </h3>
                        </div>

                        <div className="flex flex-wrap gap-4 mt-4 text-sm text-gray-600">
                          <div className="flex items-center gap-2">
                            <span className="text-gray-500">Periode:</span>
                            <span className="font-medium text-gray-900">
                              {item.period}
                            </span>
                          </div>
                          <div className="flex items-center gap-2">
                            <span className="text-gray-500">Tanggal:</span>
                            <span className="font-medium text-gray-900">
                              {item.date}
                            </span>
                          </div>
                          <div className="flex items-center gap-2">
                            <span className="text-gray-500">Ukuran File:</span>
                            <span className="font-medium text-gray-900">
                              {item.fileSize}
                            </span>
                          </div>
                        </div>
                      </div>

                      <div className="flex flex-col items-end gap-3">
                        <span
                          className={`inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium ${statusStyle.bg} ${statusStyle.text}`}
                        >
                          {getStatusIcon(item.status)}
                          {statusStyle.label}
                        </span>

                        <div className="flex gap-2">
                          <button className="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <Eye className="w-4 h-4 text-gray-600" />
                          </button>
                          <button className="px-3 py-2 bg-[#663399] text-white text-xs font-medium rounded-lg hover:bg-[#5b2d86] transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                            <Download className="w-4 h-4" />
                            Download
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                );
              })
            ) : (
              <div className="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <FileText className="w-8 h-8 text-gray-400 mx-auto mb-3" />
                <p className="text-gray-600 text-sm">Belum ada laporan</p>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
}
