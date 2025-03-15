import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Badge } from "@/components/ui/badge"
import { Search, Filter, Download, Calendar } from "lucide-react"
import { Input } from "@/components/ui/input"
import { Button } from "@/components/ui/button"
import Link from "next/link"

export default function SalesPage() {
  const sales = [
    {
      id: 1,
      date: "2023-06-15",
      customer: "Mario Rossi",
      items: "SIM Iliad + Accessori",
      provider: "Iliad",
      total: 129.0,
      status: "Completata",
    },
    {
      id: 2,
      date: "2023-06-14",
      customer: "Laura Bianchi",
      items: "SIM WindTre + Smartphone Samsung Galaxy S22",
      provider: "WindTre",
      total: 699.0,
      status: "Completata",
    },
    {
      id: 3,
      date: "2023-06-14",
      customer: "Giuseppe Verdi",
      items: "SIM Fastweb",
      provider: "Fastweb",
      total: 89.0,
      status: "Completata",
    },
    {
      id: 4,
      date: "2023-06-13",
      customer: "Francesca Conti",
      items: "Sky TV + Sky Wifi",
      provider: "Sky",
      total: 249.0,
      status: "In elaborazione",
    },
    {
      id: 5,
      date: "2023-06-12",
      customer: "Antonio Marino",
      items: "Pianeta Fibra + Accessori",
      provider: "Pianeta Fibra",
      total: 179.0,
      status: "Completata",
    },
    {
      id: 6,
      date: "2023-06-11",
      customer: "Sofia Ricci",
      items: "iPhone 13 + SIM Iliad",
      provider: "Iliad",
      total: 809.0,
      status: "Completata",
    },
    {
      id: 7,
      date: "2023-06-10",
      customer: "Luca Ferrari",
      items: "Modem Fastweb NeXXt + SIM Fastweb",
      provider: "Fastweb",
      total: 139.0,
      status: "Completata",
    },
    {
      id: 8,
      date: "2023-06-09",
      customer: "Elena Martini",
      items: "SIM WindTre + Accessori",
      provider: "WindTre",
      total: 149.0,
      status: "Annullata",
    },
  ]

  return (
    <div className="flex min-h-screen w-full flex-col">
      <main className="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h1 className="text-3xl font-bold tracking-tight">Gestione Vendite</h1>
        </div>

        <div className="flex flex-col gap-4 md:flex-row md:items-center">
          <div className="relative flex-1 w-full">
            <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input type="search" placeholder="Filtra vendite..." className="pl-8 w-full" />
          </div>
          <div className="flex flex-wrap items-center gap-2">
            <Button variant="outline" size="sm" className="w-full sm:w-auto">
              <Calendar className="mr-2 h-4 w-4" />
              Filtra per data
            </Button>
            <Button variant="outline" size="sm" className="w-full sm:w-auto">
              <Filter className="mr-2 h-4 w-4" />
              Filtra per gestore
            </Button>
            <Button variant="outline" size="icon">
              <Download className="h-4 w-4" />
              <span className="sr-only">Esporta</span>
            </Button>
          </div>
        </div>

        <div className="rounded-md border overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>ID</TableHead>
                <TableHead>Data</TableHead>
                <TableHead>Cliente</TableHead>
                <TableHead>Articoli</TableHead>
                <TableHead>Gestore</TableHead>
                <TableHead>Totale (€)</TableHead>
                <TableHead>Stato</TableHead>
                <TableHead className="text-right">Azioni</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {sales.map((sale) => (
                <TableRow key={sale.id}>
                  <TableCell className="font-medium">{sale.id}</TableCell>
                  <TableCell>{sale.date}</TableCell>
                  <TableCell>{sale.customer}</TableCell>
                  <TableCell>{sale.items}</TableCell>
                  <TableCell>{sale.provider}</TableCell>
                  <TableCell>{sale.total.toFixed(2)}</TableCell>
                  <TableCell>
                    {sale.status === "Completata" ? (
                      <Badge className="bg-green-500">Completata</Badge>
                    ) : sale.status === "In elaborazione" ? (
                      <Badge className="bg-blue-500">In elaborazione</Badge>
                    ) : (
                      <Badge className="bg-red-500">Annullata</Badge>
                    )}
                  </TableCell>
                  <TableCell className="text-right">
                    <Button variant="ghost" size="sm" asChild>
                      <Link href={`/sales/${sale.id}`}>Dettagli</Link>
                    </Button>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </div>
      </main>
    </div>
  )
}

