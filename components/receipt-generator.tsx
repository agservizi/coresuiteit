"use client"

import { jsPDF } from "jspdf"

interface ReceiptItem {
  name: string
  price: number
  quantity: number
  total: number
}

interface ReceiptProps {
  items: ReceiptItem[]
  customerName: string
  provider: string
  paymentMethod: string
  total: number
}

export function generateReceipt({ items, customerName, provider, paymentMethod, total }: ReceiptProps): void {
  try {
    // Crea un nuovo documento PDF
    const doc = new jsPDF({
      orientation: "portrait",
      unit: "mm",
      format: [80, 200], // Larghezza 80mm, altezza dinamica
    })

    // Imposta il font
    doc.setFont("helvetica", "bold")
    doc.setFontSize(10)

    // Intestazione
    const centerX = 40 // Centro del documento (80mm / 2)
    doc.text("AG SERVIZI", centerX, 10, { align: "center" })

    doc.setFontSize(8)
    doc.text("DI CAVALIERE CARMINE", centerX, 14, { align: "center" })
    doc.text("VIA PLINIO IL VECCHIO 72", centerX, 18, { align: "center" })
    doc.text("CASTELLAMMARE DI STABIA 80053 NAPOLI", centerX, 22, { align: "center" })
    doc.text("TELEFONO 0810584542 CELL/WA 3773798570", centerX, 26, { align: "center" })
    doc.text("P.IVA 08442881218", centerX, 30, { align: "center" })
    doc.text("ag.servizi16@gmail.com", centerX, 34, { align: "center" })

    // Separatore
    doc.setLineWidth(0.5)
    doc.line(5, 38, 75, 38)

    // Scontrino non fiscale
    doc.setFont("helvetica", "bold")
    doc.setFontSize(10)
    doc.text("SCONTRINO NON FISCALE", centerX, 44, { align: "center" })

    // Data e ora
    const now = new Date()
    const dateStr = now.toLocaleDateString("it-IT")
    const timeStr = now.toLocaleTimeString("it-IT")
    doc.setFontSize(8)
    doc.text(`Data: ${dateStr} Ora: ${timeStr}`, centerX, 50, { align: "center" })

    // Cliente
    if (customerName) {
      doc.text(`Cliente: ${customerName}`, centerX, 54, { align: "center" })
    }

    // Separatore
    doc.line(5, 58, 75, 58)

    // Prodotti
    let yPos = 64
    doc.setFont("helvetica", "normal")

    // Intestazione tabella
    doc.setFont("helvetica", "bold")
    doc.text("Prodotto", 5, yPos)
    doc.text("Qtà", 50, yPos)
    doc.text("Prezzo", 60, yPos)
    doc.text("Tot", 70, yPos)
    yPos += 4

    // Separatore
    doc.line(5, yPos, 75, yPos)
    yPos += 4

    // Elenco prodotti
    doc.setFont("helvetica", "normal")
    items.forEach((item) => {
      // Tronca il nome se troppo lungo
      const name = item.name.length > 25 ? item.name.substring(0, 22) + "..." : item.name
      doc.text(name, 5, yPos)
      doc.text(item.quantity.toString(), 50, yPos)
      doc.text(`€${item.price.toFixed(2)}`, 60, yPos)
      doc.text(`€${item.total.toFixed(2)}`, 70, yPos)
      yPos += 4
    })

    // Separatore
    yPos += 2
    doc.line(5, yPos, 75, yPos)
    yPos += 6

    // Totale
    doc.setFont("helvetica", "bold")
    doc.text("TOTALE", 50, yPos)
    doc.text(`€${total.toFixed(2)}`, 70, yPos)
    yPos += 6

    // Metodo di pagamento
    doc.setFont("helvetica", "normal")
    doc.text(`Pagamento: ${paymentMethod}`, centerX, yPos, { align: "center" })
    yPos += 6

    // Gestore
    doc.text(`Gestore: ${provider}`, centerX, yPos, { align: "center" })
    yPos += 10

    // Ringraziamento
    doc.text("Grazie per il vostro acquisto!", centerX, yPos, { align: "center" })
    yPos += 4
    doc.text("Arrivederci", centerX, yPos, { align: "center" })

    // Salva il PDF
    doc.save("scontrino.pdf")

    return
  } catch (error) {
    console.error("Errore nella generazione dello scontrino:", error)
    throw error
  }
}

