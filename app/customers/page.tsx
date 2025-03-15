import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Badge } from "@/components/ui/badge"
import { Search, Filter, Download } from "lucide-react"
import { Input } from "@/components/ui/input"
import { Button } from "@/components/ui/button"
import Link from "next/link"

export default function CustomersPage() {
  const customers = [
    {
      id: 1,
      name: "Mario Rossi",
      email: "mario.rossi@example.com",
      phone: "+39 333 1234567",
      purchases: 5,
      lastPurchase: "2023-06-15",
      totalSpent: 729.0,
    },
    {
      id: 2,
      name: "Laura Bianchi",
      email: "laura.bianchi@example.com",
      phone: "+39 333 7654321",
      purchases: 3,
      lastPurchase: "2023-06-14",
      totalSpent: 899.0,
    },
    {
      id: 3,
      name: "Giuseppe Verdi",
      email: "giuseppe.verdi@example.com",
      phone: "+39 333 9876543",
      purchases: 2,
      lastPurchase: "2023-06-14",
      totalSpent: 189.0,
    },
    {
      id: 4,
      name: "Francesca Conti",
      email: "francesca.conti@example.com",
      phone: "+39 333 3456789",
      purchases: 1,
      lastPurchase: "2023-06-13",
      totalSpent: 249.0,
    },
    {
      id: 5,
      name: "Antonio Marino",
      email: "antonio.marino@example.com",
      phone: "+39 333 6789012",
      purchases: 4,
      lastPurchase: "2023-06-12",
      totalSpent: 579.0,
    },
    {
      id: 6,
      name: "Sofia Ricci",
      email: "sofia.ricci@example.com",
      phone: "+39 333 2345678",
      purchases: 2,
      lastPurchase: "2023-06-11",
      totalSpent: 909.0,
    },
    {
      id: 7,
      name: "Luca Ferrari",
      email: "luca.ferrari@example.com",
      phone: "+39 333 8901234",
      purchases: 3,
      lastPurchase: "2023-06-10",
      totalSpent: 439.0,
    },
    {
      id: 8,
      name: "Elena Martini",
      email: "elena.martini@example.com",
      phone: "+39 333 4567890",
      purchases: 1,
      lastPurchase: "2023-06-09",
      totalSpent: 149.0,
    },
  ]

  return (
    <div className="flex min-h-screen w-full flex-col">
      <main className="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h1 className="text-3xl font-bold tracking-tight">Gestione Clienti</h1>
        </div>

        <div className="flex flex-col sm:flex-row items-center gap-2">
          <div className="relative flex-1 w-full">
            <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input type="search" placeholder="Filtra clienti..." className="pl-8 w-full" />
          </div>
          <div className="flex items-center gap-2 mt-2 sm:mt-0">
            <Button variant="outline" size="icon">
              <Filter className="h-4 w-4" />
              <span className="sr-only">Filtra</span>
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
                <TableHead>Nome</TableHead>
                <TableHead>Email</TableHead>
                <TableHead>Telefono</TableHead>
                <TableHead>Acquisti</TableHead>
                <TableHead>Ultimo Acquisto</TableHead>
                <TableHead>Totale Speso (€)</TableHead>
                <TableHead>Stato</TableHead>
                <TableHead className="text-right">Azioni</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {customers.map((customer) => (
                <TableRow key={customer.id}>
                  <TableCell className="font-medium">{customer.name}</TableCell>
                  <TableCell>{customer.email}</TableCell>
                  <TableCell>{customer.phone}</TableCell>
                  <TableCell>{customer.purchases}</TableCell>
                  <TableCell>{customer.lastPurchase}</TableCell>
                  <TableCell>{customer.totalSpent.toFixed(2)}</TableCell>
                  <TableCell>
                    {customer.purchases > 3 ? (
                      <Badge className="bg-green-500">Cliente Fedele</Badge>
                    ) : customer.purchases > 1 ? (
                      <Badge className="bg-blue-500">Cliente Regolare</Badge>
                    ) : (
                      <Badge className="bg-gray-500">Nuovo Cliente</Badge>
                    )}
                  </TableCell>
                  <TableCell className="text-right">
                    <Button variant="ghost" size="sm" asChild>
                      <Link href={`/customers/${customer.id}`}>Dettagli</Link>
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

