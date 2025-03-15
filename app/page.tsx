import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { CalendarDays, Package, ShoppingCart, Users, TrendingUp } from "lucide-react"
import DashboardChart from "@/components/dashboard-chart"
import RecentSales from "@/components/recent-sales"
import LowStockAlert from "@/components/low-stock-alert"

export default function Dashboard() {
  return (
    <div className="flex min-h-screen w-full flex-col">
      <main className="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <h1 className="text-3xl font-bold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-primary to-primary/70">
            Dashboard
          </h1>
        </div>
        <Tabs defaultValue="overview" className="space-y-4">
          <TabsList className="bg-muted/50 p-1 w-full overflow-x-auto">
            <TabsTrigger value="overview" className="data-[state=active]:bg-white data-[state=active]:shadow-sm">
              Panoramica
            </TabsTrigger>
            <TabsTrigger value="analytics" className="data-[state=active]:bg-white data-[state=active]:shadow-sm">
              Analisi
            </TabsTrigger>
            <TabsTrigger value="alerts" className="data-[state=active]:bg-white data-[state=active]:shadow-sm">
              Avvisi
            </TabsTrigger>
          </TabsList>
          <TabsContent value="overview" className="space-y-4">
            <div className="responsive-grid-4">
              <Card className="dashboard-card border-none shadow-md">
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Vendite Totali</CardTitle>
                  <ShoppingCart className="h-4 w-4 text-primary" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">€12,345</div>
                  <p className="text-xs text-muted-foreground flex items-center gap-1">
                    <span className="text-green-500 font-medium">+18%</span> rispetto al mese scorso
                  </p>
                </CardContent>
              </Card>
              <Card className="dashboard-card border-none shadow-md">
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Prodotti in Magazzino</CardTitle>
                  <Package className="h-4 w-4 text-primary" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">245</div>
                  <p className="text-xs text-muted-foreground flex items-center gap-1">
                    <span className="text-green-500 font-medium">+12</span> nuovi prodotti questa settimana
                  </p>
                </CardContent>
              </Card>
              <Card className="dashboard-card border-none shadow-md">
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Clienti Totali</CardTitle>
                  <Users className="h-4 w-4 text-primary" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">573</div>
                  <p className="text-xs text-muted-foreground flex items-center gap-1">
                    <span className="text-green-500 font-medium">+24</span> nuovi clienti questo mese
                  </p>
                </CardContent>
              </Card>
              <Card className="dashboard-card border-none shadow-md">
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <CardTitle className="text-sm font-medium">Vendite Giornaliere</CardTitle>
                  <CalendarDays className="h-4 w-4 text-primary" />
                </CardHeader>
                <CardContent>
                  <div className="text-2xl font-bold">€1,250</div>
                  <p className="text-xs text-muted-foreground flex items-center gap-1">
                    <span className="text-green-500 font-medium">+8%</span> rispetto a ieri
                  </p>
                </CardContent>
              </Card>
            </div>
            <div className="grid gap-4 grid-cols-1 lg:grid-cols-7">
              <Card className="col-span-1 lg:col-span-4 dashboard-card-highlight border-none shadow-md">
                <CardHeader>
                  <CardTitle>Andamento Vendite</CardTitle>
                  <CardDescription>Vendite per gestore negli ultimi 30 giorni</CardDescription>
                </CardHeader>
                <CardContent className="pl-2">
                  <DashboardChart />
                </CardContent>
              </Card>
              <Card className="col-span-1 lg:col-span-3 dashboard-card-highlight border-none shadow-md">
                <CardHeader>
                  <CardTitle>Vendite Recenti</CardTitle>
                  <CardDescription>Ultime 5 vendite effettuate</CardDescription>
                </CardHeader>
                <CardContent>
                  <RecentSales />
                </CardContent>
              </Card>
            </div>
          </TabsContent>
          <TabsContent value="analytics" className="space-y-4">
            <Card className="dashboard-card-highlight border-none shadow-md">
              <CardHeader>
                <CardTitle>Analisi Vendite per Gestore</CardTitle>
                <CardDescription>Distribuzione delle vendite tra i diversi gestori</CardDescription>
              </CardHeader>
              <CardContent className="pl-2">
                <div className="h-[400px]">
                  {/* Placeholder per grafico analisi */}
                  <div className="flex h-full items-center justify-center rounded-md border border-dashed">
                    <div className="flex flex-col items-center gap-1 text-center">
                      <TrendingUp className="h-8 w-8 text-primary/50" />
                      <div className="text-sm font-medium">Grafico analisi vendite per gestore</div>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </TabsContent>
          <TabsContent value="alerts" className="space-y-4">
            <Card className="dashboard-card-highlight border-none shadow-md">
              <CardHeader>
                <CardTitle>Avvisi Magazzino</CardTitle>
                <CardDescription>Prodotti con scorte basse che necessitano riordino</CardDescription>
              </CardHeader>
              <CardContent>
                <LowStockAlert />
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </main>
    </div>
  )
}

